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

        $view = property_exists($this, 'showView')
            ? $this->showView
            : RouteSupport::getRouteActual() . '.show';

        return view($view, compact('obj'));
    }
}
