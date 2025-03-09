<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait AsServiceDestroyTrait
{
    public function destroy(Model $model): true
    {
        return DB::transaction(function () use ($model) {
            $fieldId = $model->getKeyName();

            $id = $model->{$fieldId};

            if (method_exists($this, 'beforeDelete')) {
                $this->beforeDelete($model);
            }

            $model->delete();

            if (method_exists($this, 'afterDelete')) {
                $this->afterDelete($id);
            }

            return true;
        });

    }
}
