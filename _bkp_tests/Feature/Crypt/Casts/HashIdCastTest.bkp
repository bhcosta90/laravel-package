<?php

declare(strict_types = 1);

use CodeFusion\Crypt\Casts\HashIdCast;
use CodeFusion\Crypt\Facade\HashId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

beforeEach(function (): void {
    Config::set('hashids.enable', true);
    Config::set('hashids.default', 'main');
    Config::set('hashids.main.salt', 'test-salt');
    Config::set('hashids.main.length', 10);
    Config::set('hashids.main.alphabet', 'abcdefghijklmnopqrstuvwxyz');
    $this->hashIdCast   = new HashIdCast();
    $this->model        = Mockery::mock(Model::class);
    $this->key          = 'user_id';
    $this->value        = 123;
    $this->encodedValue = 'encoded123';
    HashId::shouldReceive('verify')->with($this->key)->andReturn(true);
    HashId::shouldReceive('encode')->with($this->value)->andReturn($this->encodedValue);
    HashId::shouldReceive('decode')->with($this->encodedValue)->andReturn($this->value);
});

test('it encodes a value correctly in get method', function (): void {
    $result = $this->hashIdCast->get($this->model, $this->key, $this->value, []);
    expect($result)->toBe($this->encodedValue);
});

test('it returns original value if hashids is disabled in get method', function (): void {
    Config::set('hashids.enable', false);
    $result = $this->hashIdCast->get($this->model, $this->key, $this->value, []);
    expect($result)->toBe($this->value);
});

test('it decodes a value correctly in set method', function (): void {
    $result = $this->hashIdCast->set($this->model, $this->key, $this->encodedValue, []);
    expect($result)->toBe($this->value);
});

test('it returns original value if hashids is disabled in set method', function (): void {
    Config::set('hashids.enable', false);
    $result = $this->hashIdCast->set($this->model, $this->key, $this->encodedValue, []);
    expect($result)->toBe($this->encodedValue);
});
