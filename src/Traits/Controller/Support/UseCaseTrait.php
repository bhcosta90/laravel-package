<?php

namespace BRCas\Laravel\Traits\Controller\Support;

use BRCas\Laravel\Support\RouteSupport;
use Illuminate\Contracts\Container\BindingResolutionException;
use Throwable;

trait UseCaseTrait
{
    public function getUseCaseClass($action, $verify = true)
    {
        $routeActual = ucwords(str_replace('-', ' ', RouteSupport::getRouteActual()));
        $routeActual = str_replace(' ', '', $routeActual);
        $routeName = ucwords(str_replace('.', ' ', $routeActual . "." . $action));
        $nameUseCase = "App\\UseCases\\" . str_replace(" ", "\\", $routeName) . "UseCase";
        try {
            return app($nameUseCase);
        } catch (BindingResolutionException $e) {
            if ($verify) {
                dd(str_replace('\\', '/', $nameUseCase . '.php'), $nameUseCase);
            }
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
