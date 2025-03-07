<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Traits;

use CodeFusion\Service\Traits\Helper\BaseQueryTrait;
use CodeFusion\Service\Traits\Helper\{SyncModelTrait};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait AsServiceUpdateTrait
{
    use BaseQueryTrait;
    use SyncModelTrait;

    public function update(Model $model, array $attributes = []): Model
    {
        $model->fill($attributes);

        return DB::transaction(function () use ($model, $attributes) {
            $model->save();
            $this->syncModel($model, $attributes);

            $model->refresh();

            return $model;
        });

    }
}
