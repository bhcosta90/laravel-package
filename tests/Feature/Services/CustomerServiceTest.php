<?php

declare(strict_types = 1);

use App\Models\{Contact, Customer};
use App\Services\CustomerService;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

beforeEach(function () {
    $this->service = app(CustomerService::class);
});

it('must create a new customer', function () {
    $customer = $this->service->store([
        'name' => 'John Doe',
    ]);

    assertDatabaseCount('customers', 1);
    assertDatabaseHas('customers', [
        'id'   => $customer->id,
        'name' => 'John Doe',
    ]);
});

it('must create a new customer with contacts', function () {
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

it('must delete the customer', function () {
    $customer = Customer::factory()->create();

    assertDatabaseCount('customers', 1);

    $this->service->destroy($customer);

    assertDatabaseCount('customers', 0);
});

it('must update the customer', function () {
    $customer = Customer::factory()
        ->hasContacts(3)
        ->hasEmails(2)
        ->create();

    assertDatabaseCount('customers', 1);
    assertDatabaseCount('contacts', 3);

    $this->service->update($customer, [
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

    $this->service->update($customer, [
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
