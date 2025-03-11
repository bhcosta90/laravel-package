<?php

declare(strict_types = 1);

namespace CodeFusion\Controller\Traits;

use CodeFusion\Controller\Traits\Helper\AsAddRequest;

trait AsControllerStoreTrait
{
    use AsAddRequest;

    abstract protected function service(): string;

    abstract protected function resource(): string;

    public function store()
    {
        $service  = app($this->service());
        $resource = $this->resource();
        $request  = app($this->request()[__FUNCTION__]);

        $data   = $request->validated();
        $params = $request->route()?->parameters() ?: [];

        $response = $service->store($data + $params);

        return new $resource($response);
    }
}
