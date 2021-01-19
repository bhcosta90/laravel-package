<?php

namespace Costa\Package\Traits\Controllers;

use Costa\Package\Exceptions\CustomException;
use Costa\Package\Traits\BaseTrait;
use Exception;
use Illuminate\Http\Resources\Json\ResourceCollection;
use ReflectionClass;
use ReflectionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

trait IndexTrait
{
    use BaseTrait;

    /**
     * @param Request $request
     * @return Application|Factory|View
     * @throws CustomException
     * @throws ReflectionException
     */
    public function index(Request $request)
    {
        try {
            $service = app($this->service());
            if($this->verifyContract() && !in_array($this->verifyContract(), class_implements($service))){
                throw new CustomException(__("Contract :contract do not implement in service :service", [
                    'contract' => $this->verifyContract(),
                    'service' => get_class($service)
                ]));
            }
            $function = $this->functionIndex() ?: $this->getNameFunction();
            if (!method_exists($service, $function)) {
                throw new CustomException(__("Method :function do not exist in service :service", [
                    'function' => $function,
                    'service' => get_class($service)
                ]));
            }

            $data = $service->$function();

            switch ($request->getContentType() || $request->has('json')) {
                case 'json':
                    $resource = $this->resource();
                    $resourceCollection = $this->resource();

                    if (method_exists($this, 'resourceCollection')) {
                        $resourceCollection = $this->resourceCollection();
                    }
                    $refClass = new ReflectionClass($resourceCollection);

                    return $refClass->isSubclassOf(ResourceCollection::class)
                        ? (new $resource($data))->additional($this->returnIndexAction())
                        : $resourceCollection::collection($data)->additional($this->returnIndexAction());

                default:
                    return view($this->getView().'.index', [
                        'results' => $data,
                    ] + $this->returnIndexAction($data));
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     */
    protected function returnIndexAction(): array
    {
        return [];
    }

    /**
     * @throws CustomException
     * @return string
     */
    public function service(): string
    {
        throw new CustomException('Service do not implemented');
    }

    /**
     * @throws CustomException
     * @return string
     */
    public function resource(): string
    {
        throw new CustomException('Resource do not implemented');
    }

    /**
     * @return null
     */
    public function functionIndex(){
        return null;
    }
}
