<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Traits;

use Illuminate\Support\Facades\DB;

trait AsServiceDestroyTrait
{
    public function destroy(string | int $id): true
    {
        $model = $this->baseQuery()->findOrFail($id);

        return DB::transaction(function () use ($model): true {
            $fieldId = $model->getKeyName();
            $id      = $model->{$fieldId};

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
