<?php

namespace BRCas\Laravel\Traits\Controller;

use BRCas\Laravel\Support\RouteSupport;
use Illuminate\Http\Request;

trait DestroyTrait
{
    use Support\ResponseTrait, Support\MethodTrait, CallTrait;

    public function destroy(Request $request)
    {
        $useCase = $this->getUseCaseClass("destroy");
        $response = $useCase->handle([
            'request' => $request->except(['_token']),
            'params' => $params = request()->route()->parameters(),
            'permissions' => $this->getMethod('permissions') ? $this->permissions() : null,
            'user' => $request->user(),
        ]);

        return $this->getResponse(
            $response['model'] ?? null,
            $response['message'] ?? "Registro excluído com sucesso",
            $response['redirect'] ?? route(RouteSupport::getRouteActual() . '.index', $params),
        );
    }
}
