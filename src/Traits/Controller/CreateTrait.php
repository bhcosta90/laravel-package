<?php

namespace BRCas\Laravel\Traits\Controller;

use BRCas\Laravel\Support\FormSupport;
use BRCas\Laravel\Support\RouteSupport;
use Illuminate\Http\Request;

trait CreateTrait
{
    use Write\StoreTrait;

    public function create(FormSupport $formSupport, Request $request)
    {
        $action = property_exists($this, 'actionStore')
            ? $this->actionStore
            : "create";

        $objService = $this->validateService([$action]);

        $form = $formSupport->run(
            $this->formCreate(),
            route(RouteSupport::getRouteActual() . '.store', $request->route()->parameters()),
            null,
            [
                'submit' => 'New'
            ]
        );

        $viewPackage = property_exists($this, 'viewPackage')
            ? $this->viewPackage . ":"
            : "";

        return view($viewPackage . RouteSupport::getRouteActual() . '.create', compact('form'));
    }
}
