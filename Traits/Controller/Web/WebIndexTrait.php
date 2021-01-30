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
        return view(
            $this->getNameView(),
            $service->webIndex($request->route()->parameters() + $request->except('_token')) + [
                'route_name' => $this->getNameRoute()
            ]);
    }
}
