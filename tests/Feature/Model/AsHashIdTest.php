<?php

declare(strict_types = 1);

use App\Models\{Customer, Order};
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    $this->customer = Customer::factory()->create();
    $this->order    = Order::query()->create([
        'customer_id' => $this->customer->id,
        'type'        => 'default',
    ]);
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

test('disable crypt: get order by hash id', function () {
    Config::set('hashids.enable', false);

    $order = Order::findOrFail($this->order->id);

    expect($order)
        ->id->toBe(1)
        ->customer_id->toBe(1)
        ->type->toBe('default');
});
