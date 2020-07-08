<?php

namespace BRCas\Traits\Controllers\Controller;

use BRCas\Traits\Queries\ExecuteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ControllerStore
{

    use ExecuteController;

    public abstract function create();

    public abstract function route();

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

    protected abstract function resource();

}
