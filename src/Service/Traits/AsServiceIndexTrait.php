<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Traits;

use CodeFusion\Service\Traits\Helper\WithIncludesTrait;
use CodeFusion\Service\Traits\Helper\{BaseQueryTrait};
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

trait AsServiceIndexTrait
{
    use WithIncludesTrait;
    use BaseQueryTrait;

    public function getAll(
        array $filters = [],
        array $includes = [],
        array $data = [],
        ?array $search = null,
        ?string $orderBy = null,
        ?string $orderDirection = null,
        ?string $paginate = null,
        ?bool $debug = false,
    ): Paginator {
        $table = app($this->model())->getTable();

        $query = $this->baseQuery($data);

        $this->withIncludes($query, $includes);

        $this->applyFilters($query, $filters);

        if ($search !== null && $search !== []) {
            $this->applySearch($query, $search);
        }

        // @codeCoverageIgnoreStart
        if ($debug === true) {
            $query->dumpRawSql();
        }
        // @codeCoverageIgnoreEnd

        $orderBy ??= $table . '.id';
        $orderDirection ??= 'asc';

        $paginate = $paginate !== null && $paginate !== '' && $paginate !== '0' ? $paginate : "paginate";

        return $query
            ->orderBy($orderBy, $orderDirection)
            ->$paginate(perPage: 10);
    }

    protected function applyFilters(Builder $model, array $filters): void
    {
        foreach ($filters as $key => $filter) {
            $nameFilter = str("by_{$key}")->camel()->toString();
            $dataFilter = collect(explode('|', $filter ?? ''))
                ->filter(fn ($item) => filled($item))
                ->toArray();

            $model->$nameFilter(array_values($dataFilter));
        }
    }

    protected function applySearch(Builder $model, array $search): void
    {
        $fieldsSearch = array_keys($search);
        $valuesSearch = array_values($search)[0];

        try {
            $model->filters($valuesSearch, $fieldsSearch);
        } catch (\BadMethodCallException) {
            throw new \BadMethodCallException("Method scopeFilters does not exist in {$this->model()}");
        }
    }
}
