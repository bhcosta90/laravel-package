<?php

namespace BRCas\Laravel\Traits\Controllers\Api;

use BRCas\Laravel\Traits\Queries\ExecuteApi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

trait ApiStore
{
    use ExecuteApi;

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
                "msg" => __('Successful registration')
            ];

            if (method_exists($this, 'route')) {
                $data += [
                    'route' => $this->route()
                ];
            }

            if (method_exists($this, 'service')) {
                $objService = call_user_func_array([$this, 'service'], []);
                if (method_exists($objService, 'store')) {
                    $obj = $objService::store(new $model, $dataSend);
                    DB::commit();
                    return (new $resource($obj))
                        ->additional($data)
                        ->response()
                        ->setStatusCode(200);
                }
            }

            $obj = $model::create($dataSend);

            if (method_exists($this, 'postCreated')) {
                $this->postCreated($obj);
            }

            DB::commit();

            return (new $resource($obj))
                ->additional($data)
                ->response()
                ->setStatusCode(200);
        });
    }

    protected abstract function rulesPost();

    protected abstract function model();

    protected abstract function resource();
}
