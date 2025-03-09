<?php

declare(strict_types = 1);

use App\Models\{Contact, Customer};
use App\Services\CustomerService;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

beforeEach(function () {
    $this->service = app(CustomerService::class);
});

test('it must create a new customer', function () {
    $customer = $this->service->store([
        'name' => 'John Doe',
    ]);

    assertDatabaseCount('customers', 1);
    assertDatabaseHas('customers', [
        'id'   => $customer->id,
        'name' => 'John Doe',
    ]);
});

test('it must create a new customer with contacts', function () {
    $customer = $this->service->store([
        'name'     => 'John Doe',
        'contacts' => [
            [
                'name' => 'Contact name',
            ],
        ],
        'emails' => [
            [
                'value' => 'test@test.com',
            ],
        ],
    ]);

    assertDatabaseCount('customers', 1);
    assertDatabaseCount('contacts', 1);
    assertDatabaseCount('emails', 1);

    assertDatabaseHas('customers', [
        'id'   => $customer->id,
        'name' => 'John Doe',
    ]);

    assertDatabaseHas('contacts', [
        'customer_id' => $customer->id,
        'name'        => 'Contact name',
    ]);

    assertDatabaseHas('emails', [
        'customer_id' => $customer->id,
        'value'       => 'test@test.com',
    ]);
});

test('get list with specific fields', function () {
    $customer = Customer::factory()
        ->hasContacts(2)
        ->create([
            'type' => 'principal',
        ]);

    /** @var Customer $response */
    $response = $this->service->getById(
        id: $customer->id,
        includes: ['contacts.customer:id,name']
    );

    $contact = $response->contacts->get(0);

    expect($response->contacts->count())->toBe(2)
        ->and($contact)
        ->customer->type->toBeNull();
});

test('it must delete the customer', function () {
    $customer = Customer::factory()->create();

    assertDatabaseCount('customers', 1);

    $this->service->destroy($customer->id);

    assertDatabaseCount('customers', 0);
});

test('it must update the customer', function () {
    $customer = Customer::factory()
        ->hasContacts(3)
        ->hasEmails(2)
        ->create();

    assertDatabaseCount('customers', 1);
    assertDatabaseCount('contacts', 3);

    $this->service->update($customer->id, [
        'name'     => 'John Doe',
        'contacts' => [
            [
                'name' => 'Contact name',
            ],
        ],
        'emails' => [
            [
                'value' => 'test@test.com',
            ],
        ],
    ]);

    assertDatabaseCount('customers', 1);
    assertDatabaseCount('contacts', 4);
    assertDatabaseCount('emails', 1);

    assertDatabaseHas('customers', [
        'id'   => $customer->id,
        'name' => 'John Doe',
    ]);

    assertDatabaseHas('contacts', [
        'customer_id' => $customer->id,
        'name'        => 'Contact name',
    ]);

    assertDatabaseHas('emails', [
        'customer_id' => $customer->id,
        'value'       => 'test@test.com',
    ]);
});

test('it must update an existing contact', function () {
    $customer = Customer::factory()
        ->create();

    $contact = Contact::factory()
        ->create([
            'customer_id' => $customer->id,
        ]);

    $this->service->update($customer->id, [
        'name'     => 'John Doe',
        'contacts' => [
            [
                'id'   => $contact->id,
                'name' => 'Contact name',
            ],
        ],
    ]);

    assertDatabaseCount('customers', 1);
    assertDatabaseCount('contacts', 1);

    assertDatabaseHas('contacts', [
        'id'   => $contact->id,
        'name' => 'Contact name',
    ]);
});

test('it must search customers by name', function () {
    Customer::factory(9)->create();
    Customer::factory()->create(['name' => 'this customer will be search']);

    $response = $this->service->getAll();

    expect($response->count())->toBe(10);

    $response = $this->service->getAll(
        search: ['search customer' => ['name']],
    );

    expect($response->count())->toBe(0);

    $response = $this->service->getAll(
        search: ['this customer' => ['name']],
    );

    expect($response->count())->toBe(1);
});
