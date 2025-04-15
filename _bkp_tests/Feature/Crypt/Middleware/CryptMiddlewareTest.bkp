<?php

declare(strict_types = 1);

use App\Http\Controller\CustomerController;
use App\Models\{Customer};
use CodeFusion\Crypt\Facade\HashId;
use CodeFusion\Crypt\Middleware\CryptMiddleware;
use CodeFusion\Crypt\Provider\CryptServiceProvider;
use Illuminate\Support\Facades\{Config, Route};

use function Pest\Laravel\{assertDatabaseHas, get, getJson, putJson};

beforeEach(function (): void {
    Config::set('app.key', 'testing');
    $this->app->register(CryptServiceProvider::class);

    $this->customer = Customer::factory()
        ->hasContacts()
        ->create(['name' => 'John Doe']);

    $this->contact = $this->customer->contacts->first();

    $this->customerId = HashId::encode($this->customer->id);
    $this->contactId  = HashId::encode($this->contact->id);

    Route::middleware(CryptMiddleware::class)
        ->apiResource('customer', CustomerController::class);

});

it('decodes route parameters', function (): void {
    Config::set('hashids.enable', true);

    $data = get(route('customer.index', [
        'includes' => 'contacts.customer',
    ]))->json();

    expect($data['data'][0]['id'])->toBe($this->customerId)
        ->and($data['data'][0]['contacts'][0]['id'])->toBe($this->contactId);

    $data = getJson(route('customer.show', [
        'customer' => $this->customerId,
    ]))->assertOk();

    expect($data['data']['id'])->toBe($this->customerId);
});

it('bypasses encryption and decryption when disabled', function (): void {
    Config::set('hashids.enable', false);

    $data = get(route('customer.index', [
        'includes' => 'contacts.customer',
    ]))->json();

    expect($data['data'][0]['id'])->toBe($this->customer->id)
        ->and($data['data'][0]['contacts'][0]['id'])->toBe($this->contact->id);

    $data = getJson(route('customer.show', [
        'customer' => $this->customer->id,
    ]))->assertOk();

    expect($data['data']['id'])->toBe($this->customer->id);
});

it('updates customer and contact names', function (): void {
    Config::set('hashids.enable', true);

    putJson(route('customer.update', [
        'customer' => $this->customerId,
    ]), [
        'name'     => 'John Doe Updated',
        'contacts' => [
            [
                'id'   => $this->contactId,
                'name' => 'John Doe Contact Updated',
            ],
        ],
    ]);

    assertDatabaseHas('customers', [
        'name' => 'John Doe Updated',
    ]);

    assertDatabaseHas('contacts', [
        'name' => 'John Doe Contact Updated',
    ]);
});
