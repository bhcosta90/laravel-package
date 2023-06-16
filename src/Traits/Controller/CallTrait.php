<?php

namespace BRCas\Laravel\Traits\Controller;

use BRCas\Laravel\Support\RouteSupport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Throwable;

trait CallTrait
{
    use Support\MethodTrait, Support\ResponseTrait;
    use Support\UseCaseTrait, Support\FormTrait;

    public function __call($action, $args2)
    {
        /** @var Request */
        $request = app(Request::class);
        $snakeCase = explode('_', str()->snake($action));
        $typeAction = array_pop($snakeCase);
        $actionSnake = str()->camel(str_replace('_', ' ', implode('_', $snakeCase)));

        if (empty($actionSnake)) {
            $actionSnake = $action;
        }

        $params = $request->route()->parameters();

        $dataForm = [];
        if (in_array($request->getMethod(), ['POST', 'PUT']) && $this->getMethod($form = $actionSnake . 'Form')) {
            $dataForm = $this->formData($this->$form());
        }

        if ($request->has('with_form') && $this->getMethod($form = $actionSnake . 'Form') == null) {
            throw new Exception($actionSnake . 'Form');
        }

        $response = $this->getUseCase($action, $request, $dataForm);

        switch ($request->getMethod()) {
            case 'PATCH':
            case 'DELETE':
            case 'PUT':
            case 'POST':
                session()->flash(RouteSupport::getRouteName(), request()->all());

                $redirect = $this->getMethod($form = $actionSnake . 'Redirect');
                if (!empty($redirect)) {
                    $redirect = $this->$redirect($response['model'] ?? null);
                }

                return $this->getResponse(
                    $response['model'] ?? null,
                    $response['message'] ?? "Registro cadastrado com sucesso",
                    $redirect ?: route(RouteSupport::getRouteActual() . '.index', $params),
                );
                break;
            default:
                $response['actions'] = [
                    'store' => $this->verifyNameRoute('store'),
                    'update' => $this->verifyNameRoute('update'),
                    'destroy' => $this->verifyNameRoute('destroy'),
                ];

                $routeForm = $action === 'edit' ? '.update' : '.store';

                if ($this->getMethod($form = $actionSnake . 'Form')) {
                    $response['form'] = $this->formGenerate(
                        $this->$form(),
                        route(RouteSupport::getRouteActual() . $routeForm, $params),
                        $response
                    );
                }

                if ($request->has('with_ajax')) {
                    return response()->json($response, $response['status'] ?? Response::HTTP_OK);
                }

                if (empty($response['redirect'])) {
                    return view(RouteSupport::getRouteActual() . '.' . $typeAction, $response);
                } else {
                    return redirect()->to($response['redirect']);
                }
        }
    }

    protected function verifyNameRoute($name)
    {
        try {
            if (Route::has(RouteSupport::getRouteActual() . '.' . $name)) {
                return route(RouteSupport::getRouteActual() . '.' . $name, request()->route()->parameters());
            }
        } catch (Throwable) {
            return null;
        }
    }

    protected function getUseCase($action, $request, $dataForm)
    {
        $response = [];

        if ($useCase = $this->getUseCaseClass($action, $request->has('with_usecase') || in_array($request->getMethod(), [
            'PATCH',
            'DELETE',
            'PUT',
            'POST',
        ]))) {
            $response = $useCase->handle([
                'request' => $request->except(['_token']),
                'params' => $request->route()->parameters(),
                'permissions' => $this->getMethod('permissions') ? $this->permissions() : null,
                'dataForm' => $dataForm,
                'user' => $request->user(),
            ]);
        }

        return $response;
    }
}
