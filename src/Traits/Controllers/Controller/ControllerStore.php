<?php

namespace BRCas\Laravel\Traits\Controllers\Controller;

use BRCas\Laravel\Utils\Message;
use Illuminate\Http\Request;

trait ControllerStore
{
    public function getMessageStore()
    {
        return Message::created();
    }

    public abstract function create();

    public function store(Request $request)
    {
        $this->request = $request;

        return $this->executeAction($request, function () {
            $dataSend = $this->validate($this->request, $this->rulesPost());

            if (method_exists($this, 'serializeArrayStore')) {
                if(is_array($result = $this->serializeArrayStore($dataSend))) {
                    $dataSend = $result;
                }
            }

            $model = $this->model();
            $objService = null;

            if (method_exists($this, 'service')) {
                $objService = call_user_func_array([$this, 'service'], []);
                if (method_exists($objService, 'store')) {
                    $objService::store($dataSend);
                    $this->request->session()->flash('success', $this->getMessageStore());
                    return redirect($this->route());
                }
            }

            $obj = $model::create($dataSend);

            if (method_exists($this, 'postCreated')) {
                $this->postCreated($obj);
            }

            $this->request->session()->flash('success', $this->getMessageStore());
            return redirect($this->route());
        });
    }

    protected abstract function rulesPost();

    protected abstract function model();

    public abstract function route();

    protected abstract function resource();
}
