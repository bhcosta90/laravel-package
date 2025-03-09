<?php

declare(strict_types = 1);

namespace CodeFusion\Model\Traits;

use Hashids\Hashids;

trait AsHashId
{
    protected static ?Hashids $hashids = null;

    public function getAttribute($key)
    {
        if (!config('hashids.enable')) {
            return parent::getAttribute($key);
        }

        if (preg_match('/^.*_id$|^id$/', (string) $key)) {
            return self::getHashId()->encode(parent::getAttribute($key));
        }

        return parent::getAttribute($key);
    }

    public static function find(mixed $id): ?self
    {
        if (config('hashids.enable')) {
            $id = self::getHashId()->decode($id);
        }

        return self::query()
            ->find($id)
            ?->first();
    }

    public static function findOrFail(mixed $id): self
    {
        if (config('hashids.enable')) {
            $id = self::getHashId()->decode($id);
        }

        return self::query()
            ->findOrFail($id)
            ?->firstOrFail();
    }

    protected static function getHashId(): Hashids
    {
        if (!self::$hashids instanceof Hashids) {
            self::$hashids = app(Hashids::class);
        }

        return self::$hashids;
    }
}
