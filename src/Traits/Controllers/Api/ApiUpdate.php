<?php

namespace BRCas\Traits\Controllers\Api;

use BRCas\Traits\Queries\Save;
use BRCas\Traits\Queries\ExecuteApi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

trait ApiUpdate
{
    use ExecuteApi;

    public function update(Request $request, $id)
    {
        $this->request = $request;

        return $this->executeAction($request, function () use ($id) {
            if (method_exists($this, 'service')) {
                $objService = call_user_func_array([$this, 'service'], []);
                if (method_exists($objService, 'find')) {
                    $obj = $objService::find($id);
                }
            }

            $objClass = $this->model();
            if (empty($obj)) {
                $obj = $objClass::findOrFail($id);
            }

            $data = [
                "status" => 200,
                "msg" => __('Successful registration')
            ];

            if (method_exists($this, 'route')) {
                $data += [
                    'route' => $this->route()
                ];
            }

            $dataSend = $this->validate($this->request, $this->rulesPut());
            $resource = $this->resource();

            if (method_exists($this, 'service')) {
                $objService = call_user_func_array([$this, 'service'], []);
                if (method_exists($objService, 'put')) {
                    $obj = $objService::put($obj, $dataSend);
                    DB::commit();
                    return (new $resource($obj))
                        ->additional($data)
                        ->response()
                        ->setStatusCode(200);
                }
            }

            $obj->update($data);

            if (method_exists($this, 'postUpdated')) {
                $this->postUpdated($obj);
            }

            DB::commit();

            $data = [
                "status" => 201,
                "msg" => __('Successful registration')
            ];

            if (method_exists($this, 'route')) {
                $data += [
                    'route' => $this->route()
                ];
            }


            return (new $resource($obj))
                ->additional($data)
                ->response()
                ->setStatusCode(200);
        });
    }

    protected abstract function model();

    protected abstract function rulesPut();

    protected abstract function resource();
}
