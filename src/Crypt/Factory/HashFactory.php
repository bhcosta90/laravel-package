<?php

declare(strict_types = 1);

namespace CodeFusion\Crypt\Factory;

use CodeFusion\Crypt\Builder\HashBuilder;
use CodeFusion\Crypt\Contracts\HashInterface;

class HashFactory
{
    public static function create(): HashInterface
    {
        return new HashBuilder();
    }
}
