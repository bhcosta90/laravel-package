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

    private $request;

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
        $requestUri = collect(explode('?', substr($this->getRequest()->getRequestUri(), 1)))->first();
        $requestUriReplace = str_replace('/', '.', $requestUri);
        $actionName = $this->getActionName();

        if (substr($requestUriReplace, -strlen($actionName)) != $actionName) {
            $requestUriReplace .= ".{$actionName}";
        }

        return $requestUriReplace;
    }

    /**
     * @return mixed
     */
    public function getRequest(): Request
    {
        return $this->request ?? request();
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    protected abstract function service();
}
