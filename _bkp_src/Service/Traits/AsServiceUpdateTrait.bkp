<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Traits;

use CodeFusion\Service\Traits\Helper\BaseQueryTrait;
use CodeFusion\Service\Traits\Helper\{AsModel, SyncModelTrait};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait AsServiceUpdateTrait
{
    use BaseQueryTrait;
    use SyncModelTrait;
    use AsModel;

    public function update(string | int $id, array $attributes = []): Model
    {
        $model = $this->baseQuery()->findOrFail($id);

        $this->setModel($model);

        if (method_exists($this, 'beforeUpdate')) {
            $attributes = $this->beforeUpdate($attributes) + $attributes;
        }

        if (method_exists($this, 'beforeSave')) {
            $attributes = $this->beforeSave($attributes) + $attributes;
        }

        $model->fill($attributes);

        return DB::transaction(function () use ($model, $attributes) {
            $model->save();

            if (method_exists($this, 'afterUpdate')) {
                $this->afterUpdate($attributes);
            }

            if (method_exists($this, 'afterSave')) {
                $this->afterSave($attributes);
            }

            $this->syncModel($model, $attributes);

            $model->refresh();

            return $model;
        });

    }
}
