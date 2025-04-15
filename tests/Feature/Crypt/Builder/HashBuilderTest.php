<?php

declare(strict_types = 1);

use CodeFusion\Crypt\Builder\HashBuilder;
use Illuminate\Support\Facades\Config;

beforeEach(function (): void {
    Config::set('hashids.default', 'main');
    Config::set('hashids.main.salt', 'test-salt');
    Config::set('hashids.main.length', 10);
    Config::set('hashids.main.alphabet', 'abcdefghijklmnopqrstuvwxyz');
    $this->hashBuilder = new HashBuilder();
});

test('it encodes a value correctly', function (): void {
    $encoded = $this->hashBuilder->encode(123);
    expect($encoded)
        ->toBeString()
        ->and($encoded)->not->toBeEmpty();
});

test('it verifies a key correctly', function (): void {
    $validKey   = 'user_id';
    $invalidKey = 'username';
    expect($this->hashBuilder->verify($validKey))
        ->toBeTrue()
        ->and($this->hashBuilder->verify($invalidKey))->toBeFalse();
});

test('it decodes a value correctly', function (): void {
    $encoded = $this->hashBuilder->encode(123);
    $decoded = $this->hashBuilder->decode($encoded);
    expect($decoded)->toBe(123);
});
