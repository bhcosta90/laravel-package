<?php

namespace CodeFusion\src\Service\Traits\Helper;

use Illuminate\Database\Eloquent\Builder;

trait AddIncludesTrait
{
    protected function addIncludes(Builder $model, array $includes = []): void
    {
        $model->with($includes);
    }
}
