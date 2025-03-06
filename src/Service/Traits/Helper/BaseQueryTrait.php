<?php

namespace CodeFusion\src\Service\Traits\Helper;

use Illuminate\Database\Eloquent\Builder;

trait BaseQueryTrait
{
    abstract protected function model(): string;

    protected function baseQuery(array $data = []): Builder
    {
        return app($this->model())->query();
    }
}
