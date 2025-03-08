<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Traits\Helper;

use Illuminate\Database\Eloquent\Model;

trait SyncModelTrait
{
    protected function syncModel($model, array $attributes): Model
    {
        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                $index = str($key)->camel()->toString();
                $model->$index()->sync($value);
            }
        }

        return $model;
    }
}
