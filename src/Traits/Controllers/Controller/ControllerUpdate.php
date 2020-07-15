<?php

namespace BRCas\Laravel\Traits\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ControllerUpdate
{

    public abstract function edit();

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        
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

            $data = $this->validate($this->request, $this->rulesPut());

            if (method_exists($this, 'service')) {
                $objService = call_user_func_array([$this, 'service'], []);
                if (method_exists($objService, 'put')) {
                    $obj = $objService::put($obj, $data);
                    DB::commit();
                    $this->request->session()->flash('success', __('Registro atualizado com sucesso'));
                    return redirect($this->route());
                }
            }

            if (method_exists($this, 'serializeArray')) {
                $data = $this->serializeArray($data);
            }

            $obj->update($data);

            if (method_exists($this, 'postUpdated')) {
                $this->postUpdated($obj);
            }

            DB::commit();
            $this->request->session()->flash('success', __('Registro atualizado com sucesso'));
            return redirect($this->route());
        });
    }

    protected abstract function model();

    protected abstract function rulesPut();

    public abstract function route();

    protected abstract function resource();
}
