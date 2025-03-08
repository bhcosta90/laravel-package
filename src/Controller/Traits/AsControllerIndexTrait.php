<?php

declare(strict_types = 1);

namespace CodeFusion\Controller\Traits;

use CodeFusion\Controller\Traits\Helper\AsValidIncludes;
use Illuminate\Http\Request;

trait AsControllerIndexTrait
{
    use AsValidIncludes;

    abstract protected function service(): string;

    abstract protected function resource(): string;

    public function index(Request $request)
    {
        $service  = app($this->service());
        $resource = $this->resource();

        $validFilters = $this->getValidFilters(
            $this->allowedFilters ?? [],
            $request->filters ?? [],
        );

        $validIncludes = $this->getValidIncludes(
            $this->allowedIncludes ?? [],
            $request->includes ?? ''
        );

        $search = $this->getSearchCriteria($request);

        $allData = $service->getAll(
            filters: $validFilters,
            includes: $validIncludes,
            search: $search,
            data: request()->route()?->parameters(),
            orderBy: $request->orderBy ?? $this->orderColumn(),
            orderDirection: $request->orderDirection ?? 'asc',
        );

        return $resource::collection($allData)
            ->additional([
                'allowed_filters'  => array_keys($validFilters),
                'allowed_includes' => $validIncludes ?? [],
            ]);
    }

    protected function getSearchCriteria(Request $request): ?array
    {
        if (empty($this->fieldSearchable) || blank($request->search)) {
            return null;
        }

        return $this->parseSearch($request->search);
    }

    protected function parseSearch(?string $search): array
    {
        if (blank($search)) {
            return [];
        }

        return collect(explode('|', $search))
            ->mapWithKeys(fn ($field) => [$field => $this->fieldSearchable])
            ->toArray();
    }

    protected function getValidFilters(array $allowedFilters, mixed $filters): mixed
    {
        $allowedFilters = array_flip($allowedFilters);

        return array_intersect_key($filters, $allowedFilters);
    }

    protected function orderColumn(): ?string
    {
        return null;
    }
}
