<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Traits\Helper;

use Illuminate\Database\Eloquent\Builder;

trait WithIncludesTrait
{
    protected function withIncludes(Builder $model, array $includes = []): void
    {
        if (method_exists($this, 'filterInclude')) {
            foreach ($this->filterInclude() as $key => $include) {
                foreach ($includes as $includeValue) {
                    if (str_contains($includeValue, $key)) {
                        $includes[$key] = $include;
                    }
                }
            }
        }

        $model->with($includes);
    }
}
