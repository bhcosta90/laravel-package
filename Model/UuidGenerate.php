<?php

namespace Costa\Package\Model;

use Illuminate\Support\Str;

trait UuidGenerate
{
    protected static function bootUuidGenerate()
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $field = $model->getFieldUuid();

                if (empty($model->{$field})) {
                    $model->{$field} = (string)Str::uuid();
                }
            }
        });
    }

    public function getIncrementing(): bool
    {
        return $this->verify();
    }

    /**
     * @return bool
     */
    private function verify(): bool
    {
        $field = $this->getKeyName();
        if (method_exists($this, 'getFieldUuid')) {
            $field = $this->getFieldUuid();
        }
        return (bool)$field == $this->getKeyName();
    }

    /**
     * @return string
     */
    protected function getFieldUuid(): string
    {
        return config('costa_package.uuid');
    }

    public function getKeyType(): string
    {
        return !$this->verify() ? 'string' : 'integer';
    }
}

