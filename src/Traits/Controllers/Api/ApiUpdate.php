<?php

namespace BRCas\Laravel\Traits\Controllers\Controller;

use BRCas\Laravel\Utils\Message;
use Illuminate\Http\Request;

trait ControllerUpdate
{
    public function getMessageUpdate()
    {
        return Message::updated();
    }

    public abstract function edit();

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

            $dataSend = $this->validate($this->request, $this->rulesPut());

            if (method_exists($this, 'serializeArrayUpdate')) {
                if(is_array($result = $this->serializeArrayUpdate($dataSend))) {
                    $dataSend = $result;
                }
            }

            if (method_exists($this, 'service')) {
                $objService = call_user_func_array([$this, 'service'], []);
                if (method_exists($objService, 'put')) {
                    $obj = $objService::put($obj, $dataSend);
                    $this->request->session()->flash('success', $this->getMessageUpdate());
                    return redirect($this->route());
                }
            }

            $obj->update($dataSend);
            $obj->save();

            $this->request->session()->flash('success', $this->getMessageUpdate());
            return redirect($this->route());
        });
    }

    protected abstract function model();

    protected abstract function rulesPut();

    public abstract function route();

    protected abstract function resource();
}
