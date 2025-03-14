<?php

declare(strict_types = 1);

namespace CodeFusion\Crypt\Builder;

use CodeFusion\Crypt\Contracts\HashInterface;
use Hashids\Hashids;

class HashBuilder implements HashInterface
{
    protected Hashids $hashId;

    public function __construct()
    {
        $connection   = config('hashids.default', 'main');
        $this->hashId = new Hashids(
            config("hashids.{$connection}.salt"),
            config("hashids.{$connection}.length"),
            config("hashids.{$connection}.alphabet")
        );
    }

    public function encode(int | string $value): string
    {
        return $this->hashId->encode($value);
    }

    public function decode(string $hashedValue): int | string
    {
        return $this->hashId->decode($hashedValue)[0];
    }

    public function verify(string $key): bool
    {
        return (bool) preg_match('/^.*_id$|^id$/', $key);
    }
}
