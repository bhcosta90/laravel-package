<?php

declare(strict_types = 1);

namespace CodeFusion\Model\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilters(Builder $query, array $fields, array $filters): void
    {
        $query->where(function ($query) use ($fields, $filters) {
            foreach ($filters as $value) {
                $query->when(
                    filled($value),
                    fn ($query) => $query->orWhereAny($fields, "like", "{$value}%")
                );
            }
        });
    }
}
