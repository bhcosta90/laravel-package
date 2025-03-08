<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Traits;

use CodeFusion\Service\Traits\Helper\BaseQueryTrait;
use CodeFusion\Service\Traits\Helper\{SyncModelTrait};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait AsServiceStoreTrait
{
    use BaseQueryTrait;
    use SyncModelTrait;

    public function store(array $attributes = []): Model
    {
        return DB::transaction(function () use ($attributes) {
            $callback = app($this->model())->create($attributes);
            $this->syncModel($callback, $attributes);

            return $callback->refresh();
        });
    }
}
