<?php

declare(strict_types = 1);

namespace CodeFusion\Crypt\Model;

use CodeFusion\Crypt\Facade\HashId;

trait AsHashId
{
    protected static ?HashId $hashids = null;

    protected static function bootAsHashId(): void
    {
        static::saving(function ($model) {
            if (config('hashids.enable')) {
                foreach ($model->getAttributes() as $key => $value) {
                    if (self::hash()::verify($key)) {
                        $model->setAttribute($key, self::hash()::decode($value));
                    }
                }
            }
        });
    }

    public function getAttribute($key)
    {
        if (!config('hashids.enable')) {
            return parent::getAttribute($key);
        }

        if (self::hash()::verify($key)) {
            return self::hash()::encode(parent::getAttribute($key));
        }

        return parent::getAttribute($key);
    }

    public static function find(mixed $id): ?self
    {
        if (config('hashids.enable')) {
            $id = self::hash()::decode($id);
        }

        return self::query()
            ->find($id)
            ?->first();
    }

    public static function findOrFail(mixed $id): self
    {
        if (config('hashids.enable')) {
            $id = self::hash()::decode($id);
        }

        return self::query()
            ->findOrFail($id)
            ?->firstOrFail();
    }

    protected static function hash(): HashId
    {
        if (static::$hashids === null) {
            static::$hashids = app(HashId::class);
        }

        return static::$hashids;
    }
}
