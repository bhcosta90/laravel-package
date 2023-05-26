<?php

namespace BRCas\Laravel\Abstracts\Traits;

use BRCas\Laravel\Support\RouteSupport;

trait ViewTrait
{
    protected function getView($view)
    {
        return $this->namespaceView() . RouteSupport::getRouteActual() . '.' . $view;
    }

    protected function namespaceView()
    {
        return null;
    }
}
