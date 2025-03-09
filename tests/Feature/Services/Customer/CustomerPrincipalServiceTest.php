<?php

declare(strict_types = 1);

use App\Models\Customer;
use App\Services\Customer\CustomerPrincipalService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

beforeEach(function () {
    $this->customerPrincipal = Customer::factory()->principal()->create();
    $this->customerDefault   = Customer::factory()->create();
    $this->service           = app(CustomerPrincipalService::class);
});

test('get list only principal', function () {
    $response = $this->service->getAll();

    expect($response->count())->toBe(1);
});

test('get customer principal by id', function () {
    $response = $this->service->getById($this->customerPrincipal->id);

    expect($response)
        ->toBeInstanceOf(Customer::class)
        ->and(fn () => $this->service->getById($this->customerDefault->id))
        ->toThrow(ModelNotFoundException::class);
});

test('update customer principal', function () {
    $response = $this->service->update($this->customerPrincipal->id, [
        'name' => 'Customer Principal Updated',
    ]);

    expect($response)
        ->toBeInstanceOf(Customer::class)
        ->and(fn () => $this->service->update($this->customerDefault->id, [
            'name' => 'Customer Principal Updated',
        ]))->toThrow(ModelNotFoundException::class);
});

test('delete customer principal', function () {
    $response = $this->service->destroy($this->customerPrincipal->id);

    expect($response)
        ->toBeTrue()
        ->and(fn () => $this->service->destroy($this->customerDefault->id))
        ->toThrow(ModelNotFoundException::class);
});
