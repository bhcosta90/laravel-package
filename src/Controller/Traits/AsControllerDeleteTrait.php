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
        $model  = $service->getById(
            id: end($params),
            data: request()->route()?->parameters()
        );

        if (blank($model)) {
            abort(Response::HTTP_NOT_FOUND, 'Resource not found.');
        }

        $service->destroy($model);

        return response()->noContent();
    }
}
