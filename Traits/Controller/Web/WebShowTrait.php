<?php


namespace Costa\Package\Traits\Controller\Web;


use Costa\Package\Traits\Controller\BaseController;

trait WebShowTrait
{
    use BaseController;

    public function show($id)
    {
        $nameView = str_replace(".{$id}", "", $this->getNameView());
        $service = app($this->service());
        return view($nameView, ['obj' => $service->find($id), "route_name" => $this->getNameRoute()]);

    }
}
