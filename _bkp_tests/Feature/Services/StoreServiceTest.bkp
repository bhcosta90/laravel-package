<?php

declare(strict_types = 1);

use App\Models\{Store};
use App\Services\{StoreService};

beforeEach(function (): void {
    $this->service = app(StoreService::class);
});

test('it must search stores by name', function (): void {
    Store::factory(9)->create();
    Store::factory()->create(['name' => 'this customer will be search']);

    $response = $this->service->getAll();

    expect($response->count())->toBe(10);

    expect(fn () => $this->service->getAll(
        search: ['search customer' => ['name']],
    ))->toThrow(BadMethodCallException::class);
});
