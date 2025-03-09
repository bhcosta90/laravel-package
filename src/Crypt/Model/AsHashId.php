<?php

declare(strict_types = 1);

namespace CodeFusion\Crypt\Model;

use CodeFusion\Crypt\Contracts\HashInterface;
use CodeFusion\Crypt\Factory\HashFactory;

trait AsHashId
{
    protected static ?HashInterface $hashids = null;

    protected static function bootAsHashId(): void
    {
        static::saving(function ($model) {
            if (config('hashids.enable')) {
                $crypt = self::getHashId();

                foreach ($model->getAttributes() as $key => $value) {
                    if ($crypt->verify($key)) {
                        $model->setAttribute($key, self::getHashId()->decode($value));
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

        if (self::getHashId()->verify($key)) {
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

    protected static function getHashId(): HashInterface
    {
        if (!self::$hashids instanceof HashInterface) {
            self::$hashids = HashFactory::create();
        }

        return self::$hashids;
    }
}
