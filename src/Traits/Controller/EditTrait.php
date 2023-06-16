<?php

namespace BRCas\Laravel\Traits\Controller;

use BRCas\Laravel\Support\RouteSupport;
use Illuminate\Http\Request;

trait EditTrait
{
    use Support\UseCaseTrait, Support\ViewTrait;
    use Support\MethodTrait, Support\FormTrait;
    use UpdateTrait, CallTrait;

    protected abstract function editForm(): string;

    public function edit(Request $request)
    {
        $useCase = $this->getUseCaseClass($action = "edit");
        $response = $useCase->handle([
            'request' => $request->except(['_token']),
            'params' => $params = request()->route()->parameters(),
            'permissions' => $this->getMethod('permissions') ? $this->permissions() : null,
        ]);

        $response['form'] = $this->formGenerate(
            $this->editForm(),
            route(RouteSupport::getRouteActual() . '.update', $params),
            $response,
        );
        return view($this->getNameView($action), $response);
    }
}
