<?php

declare(strict_types = 1);

use CodeFusion\Crypt\Provider\CryptServiceProvider;
use Hashids\Hashids;
use Illuminate\Support\Facades\{Config, Route};

beforeEach(function () {
    Config::set('app.key', 'mocked-app-key');
    $this->app->register(CryptServiceProvider::class);
});

it('registers the Hashids singleton', function () {
    $hashids = $this->app->make(Hashids::class);
    expect($hashids)->toBeInstanceOf(Hashids::class);
});

it('registers the middleware', function () {
    expect(Route::getMiddleware())->toHaveKey('code-fusion.encrypt.response')
        ->and(Route::getMiddleware())->toHaveKey('code-fusion.decrypt.response');
});
