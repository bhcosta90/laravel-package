<?php

declare(strict_types = 1);

use App\Models\Customer;
use App\Services\Customer\{CustomerContactPrincipalService};

beforeEach(function () {
    $this->customer = Customer::factory()
        ->hasContacts(3)
        ->hasContacts(2, ['is_principal' => true])
        ->create([
            'type' => "principal",
        ]);
    $this->service = app(CustomerContactPrincipalService::class);
});

test('get list only principal', function () {
    /** @var Customer $response */
    $response = $this->service->getById(
        id: $this->customer->id,
        includes: ['contacts.customer']
    );

    $contact = $response->contacts->get(0);

    expect($response->contacts->count())->toBe(2)
        ->and($contact)->is_principal->toBeTrue()
        ->customer->type->toBe('principal');
});

test('get list with specific fields and customer id', function () {

    /** @var Customer $response */
    $response = $this->service->getById(
        id: $this->customer->id,
        includes: ['contacts:customer_id,name.customer']
    );

    $contact = $response->contacts->get(0);

    expect($response->contacts->count())->toBe(2)
        ->and($contact)
        ->is_principal->toBeNull()
        ->customer->id->toBe($this->customer->id);
});
