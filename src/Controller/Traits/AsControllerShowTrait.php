<?php

declare(strict_types = 1);

namespace CodeFusion\src\Controller\Traits;

use CodeFusion\src\Controller\Traits\Helper\AsValidIncludes;
use Illuminate\Http\{Request, Response};

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

        if (blank($model)) {
            abort(Response::HTTP_NOT_FOUND, 'Resource not found.');
        }

        return new $resource($model);
    }
}
