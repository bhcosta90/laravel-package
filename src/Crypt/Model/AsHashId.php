<?php

declare(strict_types = 1);

namespace CodeFusion\Crypt\Model;

use CodeFusion\Crypt\Casts\HashIdCast;
use CodeFusion\Crypt\Facade\HashId;

trait AsHashId
{
    private static ?HashId $hashids = null;

    public function getCasts(): array
    {
        $cast = [];

        foreach ($this->getAttributes() as $attribute => $value) {
            if (self::hash()::verify($attribute)) {
                $cast[$attribute] = HashIdCast::class;
            }
        }

        return array_merge(parent::getCasts(), $cast);
    }

    private function hash(): HashId
    {
        if (static::$hashids === null) {
            static::$hashids = app(HashId::class);
        }

        return static::$hashids;
    }
}
