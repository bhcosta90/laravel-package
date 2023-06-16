<?php

namespace BRCas\Laravel\Traits\Controller;

use BRCas\Laravel\Support\RouteSupport;
use Illuminate\Http\Request;

trait CreateTrait
{
    use Support\UseCaseTrait, Support\ViewTrait;
    use Support\MethodTrait, Support\FormTrait;
    use StoreTrait, CallTrait;

    protected abstract function createForm(): string;

    public function create(Request $request)
    {
        $response = [];
        $params = request()->route()->parameters();

        if ($this->getUseCaseClass($action = "create", false)) {
            $useCase = $this->getUseCaseClass($action = "create");
            $response = $useCase->handle([
                'request' => $request->except(['_token']),
                'params' => $params,
                'permissions' => $this->getMethod('permissions') ? $this->permissions() : null,
            ]);
        }

        $response['form'] = $this->formGenerate(
            $this->createForm(),
            route(RouteSupport::getRouteActual() . '.store', $params)
        );

        return view($this->getNameView($action), $response);
    }
}
