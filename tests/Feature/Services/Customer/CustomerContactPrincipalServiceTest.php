<?php

declare(strict_types = 1);

use App\Models\Customer;
use App\Services\Customer\{CustomerContactPrincipalService};

beforeEach(function () {
    $this->customer = Customer::factory()
        ->hasContacts(3)
        ->hasContacts(2, ['is_principal' => true])
        ->create();
    $this->service = app(CustomerContactPrincipalService::class);
});

test('get list only principal', function () {
    /** @var Customer $response */
    $response = $this->service->getById(
        id: $this->customer->id,
        includes: ['contacts.customer']
    );

    expect($response->contacts->count())->toBe(2);
});

test('get list with specific fields', function () {
    /** @var Customer $response */
    $response = $this->service->getById(
        id: $this->customer->id,
        includes: ['contacts.customer:id,name']
    );

    expect($response->contacts->count())->toBe(2);
});

test('get list with specific fields and customer id', function () {

    /** @var Customer $response */
    $response = $this->service->getById(
        id: $this->customer->id,
        includes: ['contacts:customer_id,name.customer']
    );

    expect($response->contacts->count())->toBe(2);
});
