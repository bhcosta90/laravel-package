<?php

namespace BRCas\Laravel\Traits\Controllers\Api;

use BRCas\Laravel\Utils\Message;
use Illuminate\Http\Request;

trait ApiUpdate
{
    public function getMessageUpdate()
    {
        return Message::updated();
    }

    public function update(Request $request, $id)
    {
        $this->request = $request;

        return $this->executeAction($request, function () use ($id) {
            $objClass = $this->model();

            if (method_exists($this, 'service')) {
                $objService = call_user_func_array([$this, 'service'], []);
                if (method_exists($objService, 'find')) {
                    $obj = $objService::find($id);
                }
            }

            if (empty($obj)) {
                $obj = $objClass::findOrFail($id);
            }

            $data = [
                "status" => 200,
                "msg" => __('Registro atualizado com sucesso'),
            ];

            if (method_exists($this, 'route')) {
                $data += [
                    'route' => $this->route()
                ];
            }

            $dataSend = $this->validate($this->request, $this->rulesPut());

            if (method_exists($this, 'serializeArrayUpdate')) {
                if(is_array($result = $this->serializeArrayUpdate($dataSend))) {
                    $dataSend = $result;
                }
            }

            $resource = $this->resource();

            if (method_exists($this, 'service')) {
                $objService = call_user_func_array([$this, 'service'], []);
                if (method_exists($objService, 'put')) {
                    $obj = $objService::put($obj, $dataSend);

                    $this->request->session()->flash('success', $this->getMessageUpdate());

                    return $obj;
                }
            }

            $obj->update($dataSend);

            if (method_exists($this, 'route')) {
                $data += [
                    'route' => $this->route()
                ];
            }

            $this->request->session()->flash('success', $this->getMessageUpdate());

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
