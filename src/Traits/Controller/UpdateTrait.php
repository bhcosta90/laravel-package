<?php

namespace BRCas\Laravel\Traits\Controller;

use BRCas\Laravel\Support\RouteSupport;
use Illuminate\Http\Request;

trait UpdateTrait
{
    use Support\FormTrait, Support\MethodTrait, Support\ResponseTrait, CallTrait;

    public function update(Request $request)
    {
        if ($this->getMethod("updateValidate")) {
            $this->storeValidate($request->all());
        }

        $useCase = $this->getUseCaseClass("Update");

        $dataForm = $this->getMethod('editForm')
            ? $this->formData($this->editForm())
            : [];

        $response = $useCase->handle([
            'request' => $request->except(['_token']),
            'params' => $params = request()->route()->parameters(),
            'dataForm' => $dataForm,
            'permissions' => $this->getMethod('permissions') ? $this->permissions() : null,
            'user' => $request->user(),
        ]);

        return $this->getResponse(
            $response['model'],
            $response['message'] ?? "Registro alterado com sucesso",
            $response['redirect'] ?? route(RouteSupport::getRouteActual() . '.index', $params),
        );
    }
}
