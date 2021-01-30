<?php


namespace Costa\Package\Traits\Controller\Web;


use Costa\Package\Traits\Controller\BaseController;
use Illuminate\Http\Request;

trait WebShowTrait
{
    use BaseController;

    public function show($id, Request $request)
    {
        $params = [$id];
        $params += $request->route()->parameters();

        $nameView = str_replace(".{$id}", "", $this->getNameView());
        $service = app($this->service());
        return view($nameView, ['obj' => $service->find(...$params), "route_name" => $this->getNameRoute()]);

    }
}
