<?php


namespace Costa\Package\Traits\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Ramsey\Uuid\Uuid;


trait UuidGenerate
{
    /**
     * Boot function from laravel.
     */
    protected static function bootUuidGenerate()
    {
        $field = self::getFieldUuid();

        static::creating(function ($model) use ($field) {
            if (!$model->{$field}) {
                $model->{$field} = Uuid::uuid4()->toString();
            }
        });
        static::saving(function ($model) use ($field) {
            $original_uuid = $model->getOriginal($field);
            if ($original_uuid !== $model->{$field}) {
                $model->{$field} = $original_uuid;
            }
        });
    }

    public static function getFieldUuid()
    {
        return config('costa_package.default_uuid_column');
    }

    /**
     * Scope  by uuid
     * @param string  uuid of the model.
     *
     */
    public function scopeUuidGenerate($query, $uuid, $first = true)
    {
        $field = self::getFieldUuid();

        $match = preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $uuid);

        if (!is_string($uuid) || $match !== 1) {
            throw (new ModelNotFoundException)->setModel(get_class($this));
        }

        $results = $query->where($field, $uuid);

        return $first ? $results->firstOrFail() : $results;
    }


    public function getKeyType()
    {
        return $this->getFieldUuid() == 'id' ? "integer" : "string";
    }

    public function getIncrementing()
    {
        return $this->getFieldUuid() != 'id';
    }

}
