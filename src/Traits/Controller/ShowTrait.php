<?php

namespace BRCas\Laravel\Traits\Controller;

use BRCas\Laravel\Support\RouteSupport;
use BRCas\Laravel\Traits\Controller\Validation\ValidationService;
use Exception;
use Illuminate\Http\Request;

trait ShowTrait
{
    use ValidationService;

    public abstract function service();

    public function show(Request $request)
    {
        $dataParameters = $request->route()->parameters();
        $id = end($dataParameters);

        $actionFind = property_exists($this, 'actionFind')
            ? $this->actionFind
            : "find";

        $objService = $this->validateService([$actionFind]);

        $obj = $objService->$actionFind($id);

        if (empty($obj)) {
            session()->flash('error', __('Register not found'));
            return redirect()->back();
        }

        $viewPackage = property_exists($this, 'viewPackage')
            ? $this->viewPackage . ":"
            : "";

        return view($viewPackage . RouteSupport::getRouteActual() . '.show', compact('obj'));
    }
}
