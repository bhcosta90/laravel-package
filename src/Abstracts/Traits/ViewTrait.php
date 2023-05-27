<?php

namespace BRCas\Laravel\Abstracts\Traits;

use BRCas\Laravel\Support\RouteSupport;

trait ViewTrait
{
    protected function getView($view)
    {
        return $this->namespaceView() . '.' . $view;
    }

    protected function namespaceView()
    {
        return RouteSupport::getRouteActual();
    }
}
