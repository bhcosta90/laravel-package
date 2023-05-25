<?php

namespace BRCas\Laravel\Traits\Controller;

use BRCas\Laravel\Support\FormSupport;
use BRCas\Laravel\Support\RouteSupport;
use Illuminate\Http\Request;

trait EditTrait
{
    use Write\UpdateTrait;

    public function edit(FormSupport $formSupport, Request $request)
    {
        $actionFind = property_exists($this, 'actionFind')
            ? $this->actionFind
            : "find";

        $actionEdit = property_exists($this, 'actionEdit')
            ? $this->actionEdit
            : "edit";

        $objService = $this->validateService([$actionFind, $actionEdit]);

        $dataParameters = $request->route()->parameters();
        $id = end($dataParameters);

        $obj = $objService->$actionFind($id);

        if (empty($obj)) {
            session()->flash('error', __('Register not found'));
            return redirect()->back();
        }

        $form = $formSupport->run(
            $this->formEdit(),
            route(RouteSupport::getRouteActual() . '.update', $dataParameters),
            $obj,
            [
                'submit' => 'Update'
            ]
        );

        $viewPackage = property_exists($this, 'viewPackage')
            ? $this->viewPackage . ":"
            : "";

        return view($viewPackage . RouteSupport::getRouteActual() . '.edit', compact('form'));
    }
}
