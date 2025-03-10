<?php

declare(strict_types = 1);

namespace CodeFusion\Controller\Traits;

trait AsControllerStoreTrait
{
    abstract protected function service(): string;

    abstract protected function resource(): string;

    abstract protected function requestStore(): string;

    public function store()
    {
        $service  = app($this->service());
        $resource = $this->resource();
        $request  = app($this->requestStore());

        $data   = $request->validated();
        $params = $request->route()?->parameters() ?: [];

        $response = $service->store($data + $params);

        return new $resource($response);
    }
}
