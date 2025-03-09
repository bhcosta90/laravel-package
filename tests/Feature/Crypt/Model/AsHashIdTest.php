<?php

declare(strict_types = 1);

use App\Models\{Customer, Order};
use CodeFusion\Crypt\Provider\CryptServiceProvider;
use Hashids\Hashids;
use Illuminate\Support\Facades\Config;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

beforeEach(function () {
    putenv('APP_KEY=mocked-app-key');
    $this->app->register(CryptServiceProvider::class);

    $this->customer = Customer::factory()->create();
    $this->order    = Order::query()->create([
        'customer_id' => $this->customer->id,
        'type'        => 'default',
    ]);
    $this->hash = app(Hashids::class);
});

test('enable crypt: order creation with hashids', function () {
    Config::set('hashids.enable', true);

    expect($this->order)
        ->id->not->toBe(1)
        ->customer_id->not->toBe(1)
        ->type->toBe('default');

    $order = Order::findOrFail($this->order->id);
    expect($order)->toBeInstanceOf(Order::class);

    $order = Order::find($this->order->id);
    expect($order)->toBeInstanceOf(Order::class);
});

test('enable crypt: get order by hash id', function () {
    Config::set('hashids.enable', true);

    $order = Order::findOrFail($this->order->id);

    expect($order)
        ->id->not->toBe(1)
        ->customer_id->not->toBe(1)
        ->type->toBe('default');
});

test('disable crypt: order creation with hashids', function () {
    Config::set('hashids.enable', false);

    expect($this->order)
        ->id->toBe(1)
        ->customer_id->toBe(1)
        ->type->toBe('default');

    $order = Order::findOrFail($this->order->id);
    expect($order)->toBeInstanceOf(Order::class);

    $order = Order::find($this->order->id);
    expect($order)->toBeInstanceOf(Order::class);
});

test('xablau', function () {
    Config::set('hashids.enable', true);

    $idCustomer = $this->hash->encode($this->customer->id);
    $order      = Order::create([
        'customer_id' => $idCustomer,
    ]);

    assertDatabaseCount('orders', 2);
    assertDatabaseHas('orders', [
        'id'          => $this->hash->decode($order->id),
        'customer_id' => $this->customer->id,
    ]);
});

test('disable crypt: get order by hash id', function () {
    Config::set('hashids.enable', false);

    $order = Order::findOrFail($this->order->id);

    expect($order)
        ->id->toBe(1)
        ->customer_id->toBe(1)
        ->type->toBe('default');
});
