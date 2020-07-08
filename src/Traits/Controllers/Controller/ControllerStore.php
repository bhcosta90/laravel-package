<?php

namespace BRCas\Laravel\Traits\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ControllerStore
{

    public abstract function create();

    public function store(Request $request)
    {
        return $this->executeAction($request, function () {
            $data = $this->validate($this->request, $this->rulesPost());
            $model = $this->model();
            $objService = null;

            if (method_exists($this, 'service')) {
                $objService = call_user_func_array([$this, 'service'], []);
                if (method_exists($objService, 'store')) {
                    $objService::store(new $model, $data);
                    DB::commit();
                    $this->request->session()->flash('success', __('Registro cadastrado com sucesso'));
                    return redirect($this->route());
                }
            }

            if (method_exists($this, 'serializeArray')) {
                $data = $this->serializeArray($data);
            }

            $obj = $model::create($data);

            if (method_exists($this, 'postCreated')) {
                $this->postCreated($obj);
            }

            DB::commit();
            $this->request->session()->flash('success', __('Registro cadastrado com sucesso'));
            return redirect($this->route());
        });
    }

    protected abstract function rulesPost();

    protected abstract function model();

    public abstract function route();

    protected abstract function resource();

}
