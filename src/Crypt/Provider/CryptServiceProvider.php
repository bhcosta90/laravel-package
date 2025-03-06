<?php

namespace CodeFusion\src\Crypt\Provider;

use CodeFusion\src\Crypt\Middleware\{EncryptResponseMiddleware};
use CodeFusion\src\Crypt\Middleware\DecryptResponseMiddleware;
use Hashids\Hashids;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CryptServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Hashids::class, function ($app) {

            $connection = config('hashids.default', 'main');

            return new Hashids(
                config("hashids.{$connection}.salt", config('app.key')),
                config("hashids.{$connection}.length", 10),
                config("hashids.{$connection}.alphabet", 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')
            );
        });

        Route::aliasMiddleware('code-fusion.encrypt.response', EncryptResponseMiddleware::class);
        Route::aliasMiddleware('code-fusion.decrypt.response', DecryptResponseMiddleware::class);
    }
}
