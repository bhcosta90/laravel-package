<?php

declare(strict_types = 1);

namespace CodeFusion\Crypt\Provider;

use Illuminate\Support\ServiceProvider;

class CryptServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/hashids.php', 'hashids');
    }
}
