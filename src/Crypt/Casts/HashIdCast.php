<?php

declare(strict_types = 1);

namespace CodeFusion\Crypt\Casts;

use CodeFusion\Crypt\Facade\HashId;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class HashIdCast implements CastsAttributes
{
    protected static ?HashId $hashids = null;

    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value && config('hashids.enable') && is_numeric($value) && self::hash()::verify($key)) {
            return self::hash()::encode($value);
        }

        return $value;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value && config('hashids.enable') && !is_numeric($value) && self::hash()::verify($key)) {
            return self::hash()::decode($value);
        }

        return $value;
    }

    protected function hash(): HashId
    {
        if (!static::$hashids instanceof HashId) {
            static::$hashids = app(HashId::class);
        }

        return static::$hashids;
    }
}
