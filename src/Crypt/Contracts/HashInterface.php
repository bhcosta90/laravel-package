<?php

declare(strict_types = 1);

namespace CodeFusion\Crypt\Contracts;

interface HashInterface
{
    public function encode(string | int $value): string;

    public function verify(string $hashedValue): bool;

    public function decode(string $hashedValue): int | string;
}
