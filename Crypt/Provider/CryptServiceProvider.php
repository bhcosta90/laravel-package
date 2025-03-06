<?php

namespace CodeFusion\Crypt\Provider;

use CodeFusion\Crypt\Middleware\{DecryptResponseMiddleware, EncryptResponseMiddleware};
use Hashids\Hashids;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CryptServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Hashids::class, function ($app) {
            return new Hashids(
                config('app.key'),
                10,
                'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
            );
        });

        Route::aliasMiddleware('code-fusion.encrypt.response', EncryptResponseMiddleware::class);
        Route::aliasMiddleware('code-fusion.decrypt.response', DecryptResponseMiddleware::class);
    }
}
