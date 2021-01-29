<?php


namespace Costa\Package\Traits\Controller\Api;


use Costa\Package\Traits\Controller\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use ReflectionClass;

trait ApiIndexTrait
{
    use BaseController;

    public function index(Request $request)
    {
        $service = app($this->service());

        $resource = $this->resource();
        $resourceCollection = $this->resource();

        if (method_exists($this, 'resourceCollection')) {
            $resourceCollection = $this->resourceCollection();
        }
        $refClass = new ReflectionClass($resourceCollection);

        $result = $service->apiIndex($request->except('_token'));

        return $refClass->isSubclassOf(ResourceCollection::class)
            ? (new $resource($result))
            : $resourceCollection::collection($result);
    }

    protected abstract function resource();
}
