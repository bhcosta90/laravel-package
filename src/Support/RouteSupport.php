<?php

namespace BRCas\Laravel\Support;

class RouteSupport
{
    public static function getRouteActual()
    {
        $dataRoute = explode('.', self::getRouteName());
        unset($dataRoute[count($dataRoute) - 1]);
        return implode('.', $dataRoute);
    }

    public function routeIsIndex() {
        $arrayRoute = explode('.', $this->getRouteName());
        return end($arrayRoute) === 'index';
    }

    public static function getRouteName()
    {
        return request()->route()->getName();
    }
}
