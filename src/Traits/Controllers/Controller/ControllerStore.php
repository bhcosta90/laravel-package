<?php

namespace BRCas\Laravel\Traits\Controllers\Controller;

use Illuminate\Http\Request;

trait ControllerStore
{

    public abstract function create();

    public function store(Request $request)
    {
        $this->request = $request;

        return $this->executeAction($request, function () {
            $data = $this->validate($this->request, $this->rulesPost());

            if (method_exists($this, 'serializeArray')) {
                $ret = $this->serializeArray($data);
                if (is_array($ret)) {
                    $data = $ret;
                }
            }

            $model = $this->model();
            $objService = null;

            if (method_exists($this, 'service')) {
                $objService = call_user_func_array([$this, 'service'], []);
                if (method_exists($objService, 'store')) {
                    $objService::store($data);
                    $this->request->session()->flash('success', __('Registro cadastrado com sucesso'));
                    return redirect($this->route());
                }
            }

            $obj = $model::create($data);

            if (method_exists($this, 'postCreated')) {
                $this->postCreated($obj);
            }

            $this->request->session()->flash('success', __('Registro cadastrado com sucesso'));
            return redirect($this->route());
        });
    }

    protected abstract function rulesPost();

    protected abstract function model();

    public abstract function route();

    protected abstract function resource();
}
