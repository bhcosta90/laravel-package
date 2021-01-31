<?php


namespace Costa\Package\Traits\Controller\Web;


use Costa\Package\Traits\Controller\BaseController;
use Illuminate\Http\Request;

trait WebShowTrait
{
    use BaseController;

    public function show(Request $request, ...$params)
    {
        $this->request = $request;

        $id = array_pop($params);
        $service = app($this->service());
        $obj = $service->find($id, ...$params);

        $data = [
            'obj' => $obj,
        ];
        if (method_exists($service, 'show')) {
            $data += $service->show(...array_values($this->request->route()->parameters));
        }

        return view($this->getNameView() . "." . __FUNCTION__, ["route_name" => $this->getNameRoute()] + $data);

    }
}
