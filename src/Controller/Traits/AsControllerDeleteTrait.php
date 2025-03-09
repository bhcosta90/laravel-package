<?php

declare(strict_types = 1);

namespace CodeFusion\Controller\Traits;

use Illuminate\Http\{Request, Response};

trait AsControllerDeleteTrait
{
    abstract protected function service(): string;

    public function destroy(Request $request): Response
    {
        $service = app($this->service());

        $params = $request->route()?->parameters() ?: [];

        $service->destroy(end($params), $params);

        return response()->noContent();
    }
}
