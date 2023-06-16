<?php

namespace BRCas\Laravel\Traits\Controller;

use BRCas\Laravel\Support\RouteSupport;
use Illuminate\Http\Request;

trait StoreTrait
{
    use Support\FormTrait, Support\MethodTrait, Support\ResponseTrait, CallTrait;

    public function store(Request $request)
    {
        if ($this->getMethod("storeValidate")) {
            $this->storeValidate($request->all());
        }

        $useCase = $this->getUseCaseClass("Store");

        $dataForm = $this->getMethod('createForm')
            ? $this->formData($this->createForm())
            : [];

        $response = $useCase->handle([
            'request' => $request->except(['_token']),
            'params' => $params = request()->route()->parameters(),
            'dataForm' => $dataForm,
            'permissions' => $this->getMethod('permissions') ? $this->permissions() : null,
            'user' => $request->user(),
        ]);

        return $this->getResponse(
            $response['model'] ?? null,
            $response['message'] ?? "Registro cadastrado com sucesso",
            $response['redirect'] ?? route(RouteSupport::getRouteActual() . '.index', $params),
        );
    }
}
