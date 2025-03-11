<?php

declare(strict_types = 1);

namespace CodeFusion\Controller\Traits;

trait AsControllerUpdateTrait
{
    abstract protected function service(): string;

    abstract protected function resource(): string;

    public function update()
    {
        $service  = app($this->service());
        $resource = $this->resource();
        $request  = app($this->request()[__FUNCTION__]);

        $params = $request->route()?->parameters() ?: [];
        $data   = $request->validated();

        $response = $service->update(end($params), $data + $params);

        return new $resource($response);
    }
}
