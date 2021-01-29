<?php


namespace Costa\Package\Traits\Controller\Api;


use Costa\Package\Traits\Controller\BaseController;

trait ApiShowTrait
{
    use BaseController;

    public function show($id)
    {
        $service = app($this->service());
        $resource = $this->resource();
        return new $resource($service->find($id));
    }

    protected abstract function resource();
}
