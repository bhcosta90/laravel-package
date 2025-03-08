<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Traits\Helper;

use Illuminate\Database\Eloquent\Builder;

trait AddIncludesTrait
{
    protected function addIncludes(Builder $model, array $includes = []): void
    {
        $model->with($includes);
    }
}
