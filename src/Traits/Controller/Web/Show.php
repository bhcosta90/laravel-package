<?php

namespace BRCas\Laravel\Traits\Controller\Web;

use BRCas\Laravel\Traits\Support\ExecuteTrait;
use Exception;

trait Show
{
    public abstract function service();

    public abstract function permissions();

    public abstract function showView();

    public function show($id)
    {
        $objService = app($this->service());

        if (!method_exists($objService, 'find')) throw new Exception(__('Method find not found in service'));

        $obj = $objService->find($id);

        return view($this->showView(), compact('obj'));
    }
}