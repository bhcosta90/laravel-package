<?php

namespace Costa\Package\Traits\Controllers;

use App\Exceptions\WebException;
use Costa\Package\Traits\BaseTrait;;
use Exception;
use Illuminate\Http\Resources\Json\ResourceCollection;
use ReflectionClass;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

trait ShowTrait
{
    use BaseTrait;

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     * @throws WebException
     */
    public function show(Request $request, $id)
    {
        try {
            $service = app($this->service());
            $function = $this->functionShow() ?: $this->getNameFunction();
            if (!method_exists($service, $function)) {
                throw new WebException(__("Method :function do not exist in service :service", [
                    'function' => $function,
                    'service' => get_class($service)
                ]));
            }

            $data = $service->$function($id);

            switch ($request->getContentType() || $request->has('json')) {
                case 'json':
                    $resource = $this->resource();
                    return (new $resource($data))->additional($this->returnShowAction());
                default:
                    return view($this->getView().'.show', [
                        'rs' => $data,
                    ] + $this->returnShowAction());
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     */
    protected function returnShowAction(): array
    {
        return [];
    }

    /**
     * @throws WebException
     * @return string
     */
    public function service(): string
    {
        throw new WebException('Service do not implemented');
    }

    /**
     * @throws WebException
     * @return string
     */
    public function resource(): string
    {
        throw new WebException('Resource do not implemented');
    }

    /**
     * @return null
     */
    public function functionShow()
    {
        return null;
    }
}
