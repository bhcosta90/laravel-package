<?php


namespace Package\Traits\Model;


use Ramsey\Uuid\Uuid;

trait GenerateUuidTrait
{
    public static function bootGenerateUuidTrait()
    {
        static::creating(function ($obj) {
            $field = $obj->getKeyName();

            if (!empty(self::$uuid)) {
                $field = self::$uuid;
            }

            $obj->{$field} = $obj->{$field} ?: (string)self::getUuid();
        });
    }

    public static function getUuid()
    {
        return (string) Uuid::uuid4();
    }

    public function getKeyType()
    {
        return 'string';
    }

    public function getIncrementing()
    {
        return false;
    }
}