<?php

namespace BRCas\Laravel\Traits\Controllers\Api;

use Illuminate\Http\Request;

trait ApiStore
{

    public function store(Request $request)
    {
        $this->request = $request;

        return $this->executeAction($request, function () {
            if (method_exists($this, 'validateStore')) {
                $this->validateStore();
            }

            $dataSend = $this->validate($this->request, $this->rulesPost());
            $model = $this->model();
            $resource = $this->resource();
            $objService = null;

            $data = [
                "status" => 201,
                "msg" => __('Registrado com sucesso')
            ];

            if (method_exists($this, 'route')) {
                $data += [
                    'route' => $this->route()
                ];
            }

            if (method_exists($this, 'service')) {
                $objService = call_user_func_array([$this, 'service'], []);
                if (method_exists($objService, 'store')) {
                    $obj = $objService::store($dataSend);
                    return $obj;
                }
            }

            $obj = $model::create($dataSend);

            return (new $resource($obj))
                ->additional($data)
                ->response()
                ->setStatusCode(201);
        });
    }

    protected abstract function rulesPost();

    protected abstract function model();

    protected abstract function resource();
}
