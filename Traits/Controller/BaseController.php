<?php


namespace Costa\Package\Traits\Controller;


use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as FacadeRoute;

trait BaseController
{
    private $request;

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

    protected function prefixNameView(): string {
        return '';
    }

    protected  function getNameView(): string
    {
        $requestUri = collect(explode('?', substr($this->getRequest()->getRequestUri(), 1)))->first();
        $requestUriReplace = str_replace('/', '.', $requestUri);
        $actionName = $this->getActionName();

        if(substr($requestUriReplace, -strlen($actionName)) != $actionName) {
            $requestUriReplace .= ".{$actionName}";
        }

        return $this->prefixNameView() . $requestUriReplace;
    }

    protected abstract function service();
}
