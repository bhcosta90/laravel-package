<?php

declare(strict_types = 1);

namespace CodeFusion\Controller\Traits;

use CodeFusion\Controller\Traits\Helper\AsValidIncludes;
use Illuminate\Http\{Request};

trait AsControllerShowTrait
{
    use AsValidIncludes;

    abstract protected function service(): string;

    abstract protected function resource(): string;

    public function show(Request $request)
    {
        $service  = app($this->service());
        $resource = $this->resource();

        $params = $request->route()?->parameters() ?: [];

        $validIncludes = $this->getValidIncludes(
            $this->allowedIncludes ?? [],
            $request->includes ?? ''
        );

        $model = $service->getById(
            id: end($params),
            includes: $validIncludes,
            data: request()->route()?->parameters()
        );

        return new $resource($model);
    }
}
