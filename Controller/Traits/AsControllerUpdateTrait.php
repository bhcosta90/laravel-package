<?php

declare(strict_types = 1);

namespace CodeFusion\Controller\Traits;

use Illuminate\Http\Response;

trait AsControllerUpdateTrait
{
    abstract protected function service(): string;

    abstract protected function resource(): string;

    abstract protected function requestUpdate(): string;

    public function update()
    {
        $service  = app($this->service());
        $resource = $this->resource();
        $request  = app($this->requestUpdate());

        $params = $request->route()?->parameters() ?: [];
        $model  = $service->getById(
            id: end($params),
            data: request()->route()?->parameters()
        );

        if (blank($model)) {
            abort(Response::HTTP_NOT_FOUND, 'Resource not found.');
        }

        $data = $request->validated();

        $response = $service->update($model, $data);

        return new $resource($response);
    }
}
