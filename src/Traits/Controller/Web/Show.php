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

        if (!in_array(\BRCas\Laravel\Contracts\Show::class, class_implements($objService)))
            throw new Exception(__('Interface '.\BRCas\Laravel\Contracts\Show::class.' not found in service'));

        $obj = $objService->find($id);

        return view($this->showView(), compact('obj'));
    }
}