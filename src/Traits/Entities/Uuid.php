<?php


namespace BRCas\Traits\Entities;

use Ramsey\Uuid\Uuid as RamseyUuid;

trait Uuid
{
    public static function bootUuid()
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
        return (string)RamseyUuid::uuid4();
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
