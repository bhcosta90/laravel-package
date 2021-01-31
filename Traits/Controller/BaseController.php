<?php


namespace Costa\Package\Traits\Controller;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as FacadeRoute;

trait BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $request;

    protected function getNameRoute(): string
    {
        $action = $this->getActionName();
        return substr(FacadeRoute::currentRouteName(), 0, -strlen($action) - 1);
    }

    protected function getActionName(): string
    {
        $action = app(Route::class);
        return collect(explode('@', $action->getActionName()))->last();
    }

    protected function getNameView(): string
    {
        return $this->getNameRoute();
    }

    protected abstract function service();
}
