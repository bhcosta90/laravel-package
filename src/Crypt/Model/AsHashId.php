<?php

declare(strict_types = 1);

namespace CodeFusion\Crypt\Model;

use CodeFusion\Crypt\Casts\HashIdCast;
use CodeFusion\Crypt\Facade\HashId;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\{Builder, Model};

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
        if (!static::$hashids instanceof \CodeFusion\Crypt\Facade\HashId) {
            static::$hashids = app(HashId::class);
        }

        return static::$hashids;
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        if ($field !== null) {
            return parent::resolveRouteBinding($value, $field);
        }

        return $this->find(self::hash()::decode($value));
    }

    public function resolveRouteBindingQuery($query, $value, $field = null): Builder | Relation
    {
        $id = $this->getModel()->keyFromHashId($value);

        return $query->where($field ?? $this->getRouteKeyName(), $id);
    }

    public function getRouteKey(): string
    {
        return $this->hashId;
    }
}
