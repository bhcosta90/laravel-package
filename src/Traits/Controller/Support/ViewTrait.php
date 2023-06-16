<?php

namespace BRCas\Laravel\Traits\Controller\Support;

use BRCas\Laravel\Support\RouteSupport;

trait ViewTrait
{
    protected function getNameView($view)
    {
        return $this->namespaceView() . '.' . $view;
    }

    protected function namespaceView()
    {
        return RouteSupport::getRouteActual();
    }
}
