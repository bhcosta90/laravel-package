<?php

declare(strict_types = 1);

use App\Http\Controller\CustomerController;
use App\Models\{Contact, Customer};
use Illuminate\Database\Events\{TransactionBeginning};
use Illuminate\Support\Facades\{Route};

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas, delete, get, post, postJson, put};

beforeEach(function (): void {
    Route::apiResource('customer', CustomerController::class);

    $this->customer01 = Customer::factory()->create(['name' => 'John Doe']);
    $this->customer02 = Customer::factory()->create(['name' => 'Jane Doe']);

    Contact::factory()->create(['customer_id' => $this->customer01->id]);
    Contact::factory()->create(['customer_id' => $this->customer02->id]);

    Customer::factory(4)->create();
});

test('search for customer by name', function (): void {
    $response = get(route('customer.index', [
        'search' => '',
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(6, 'data');

    $response = get(route('customer.index', [
        'search' => 'John Do',
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data');

    $response = get(route('customer.index', [
        'filters' => [
            'name' => 'Jane Doe|John Doe',
        ],
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('include contacts in customer data', function (): void {
    $response = get(route('customer.index', [
        'includes' => 'contacts',
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(6, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'contacts' => [
                        '*' => [
                            'id',
                            'customer_id',
                            'name',
                        ],
                    ],
                ],
            ],
        ]);

    $response = get(route('customer.index', [
        'includes' => 'contacts.customer',
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(6, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'contacts' => [
                        '*' => [
                            'id',
                            'customer_id',
                            'name',
                            'customer' => [
                                'id',
                                'name',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
});

test('store and update customer', function (): void {

    postJson(route('customer.store'))
        ->assertJsonStructure([
            'rules' => [
                'name' => [],
            ],
        ])
        ->assertStatus(422);

    $response = post(route('customer.store', [
        'name' => 'testing 2',
    ]))->assertStatus(201);

    assertDatabaseCount('customers', 7);
    assertDatabaseHas('customers', [
        'name' => 'testing 2',
    ]);

    put(route('customer.update', [
        'customer' => $response->json('data.id'),
    ]))->assertJsonStructure([
        'rules' => [
            'name' => [],
        ],
    ])->assertStatus(422);

    put(route('customer.update', [
        'customer' => $response->json('data.id'),
    ]), [
        'name' => 'testing 3',
    ])->assertStatus(200);

    assertDatabaseCount('customers', 7);
    assertDatabaseHas('customers', [
        'name' => 'testing 3',
    ]);

    put(route('customer.update', [
        'customer' => 0,
    ]), [
        'name' => 'testing 3',
    ])->assertStatus(404);
});

test('transaction beginning event with store', function (): void {
    Event::listen(TransactionBeginning::class, function (): void {
        throw new Exception('begin transaction');
    });

    expect(fn () => post(route('customer.store', [
        'name' => 'testing 2',
    ]))->assertOk())->toThrow(Exception::class);
});

test('transaction beginning event with update', function (): void {
    Event::listen(TransactionBeginning::class, function (): void {
        throw new Exception('begin transaction');
    });

    expect(fn () => post(route('customer.store', [
        'name' => 'testing 2',
    ]))->assertStatus(201))->toThrow(Exception::class);
});

test('show customer', function (): void {
    $response = get(route('customer.show', [
        'customer' => $this->customer01->id,
        'includes' => 'contacts',
    ]))->assertOk();

    $response->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'contacts' => [
                '*' => [
                    'id',
                    'customer_id',
                    'name',
                ],
            ],
        ],
    ]);

    get(route('customer.show', [
        'customer' => 0,
        'includes' => 'contacts',
    ]))->assertNotFound();
});

test('delete customer', function (): void {
    delete(route('customer.destroy', [
        'customer' => $this->customer01->id,
    ]))->assertNoContent();

    delete(route('customer.destroy', [
        'customer' => 0,
    ]))->assertNotFound();
});
