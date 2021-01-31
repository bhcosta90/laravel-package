<?php


namespace Costa\Package\Traits\Controller\Web;


use Costa\Package\Traits\Controller\BaseController;
use Illuminate\Http\Request;

trait WebIndexTrait
{
    use BaseController;

    public function index(Request $request)
    {
        $service = app($this->service());
        $data = [
            $filter = $request->except('_token'),
            $request->route()->parameters
        ];
        return view($this->getNameView()."." . __FUNCTION__, [
            'route_name' => $this->getNameRoute(),
            'filter' => $filter
        ] + $service->index(...$data));
    }
}
