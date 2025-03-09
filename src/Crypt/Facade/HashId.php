<?php

declare(strict_types = 1);

namespace CodeFusion\Crypt\Facade;

use CodeFusion\Crypt\Builder\HashBuilder;
use Illuminate\Support\Facades\Facade;

/**
 * @see HashBuilder
 */
class HashId extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return HashBuilder::class;
    }
}
