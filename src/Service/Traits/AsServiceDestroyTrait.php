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
            $model->delete();

            return true;
        });

    }
}
