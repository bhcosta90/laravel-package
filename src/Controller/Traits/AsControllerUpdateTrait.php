<?php

declare(strict_types = 1);

namespace CodeFusion\Controller\Traits;

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
        $data   = $request->validated();

        $response = $service->update(end($params), $data + $params);

        return new $resource($response);
    }
}
