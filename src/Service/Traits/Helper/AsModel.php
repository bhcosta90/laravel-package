<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Traits\Helper;

use Illuminate\Database\Eloquent\Model;

trait AsModel
{
    protected ?Model $model = null;

    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
