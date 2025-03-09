<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Traits;

use CodeFusion\Service\Traits\Helper\BaseQueryTrait;
use CodeFusion\Service\Traits\Helper\{AsModel, SyncModelTrait};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait AsServiceStoreTrait
{
    use BaseQueryTrait;
    use SyncModelTrait;
    use AsModel;

    public function store(array $attributes = []): Model
    {
        if (method_exists($this, 'beforeStore')) {
            $attributes = $this->beforeStore($attributes) + $attributes;
        }

        if (method_exists($this, 'beforeSave')) {
            $attributes = $this->beforeSave($attributes) + $attributes;
        }

        return DB::transaction(function () use ($attributes) {
            $callback = app($this->model())->create($attributes);

            $this->setModel($callback);

            if (method_exists($this, 'afterSave')) {
                $this->afterSave($attributes);
            }

            if (method_exists($this, 'afterStore')) {
                $this->afterStore($attributes);
            }

            $this->syncModel($callback, $attributes);

            return $callback->refresh();
        });
    }
}
