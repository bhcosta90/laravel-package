<?php

namespace BRCas\Laravel\Traits\Controller;

use BRCas\Laravel\Support\RouteSupport;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\{Builder, Collection};
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Route;

trait IndexTrait
{
    use Support\UseCaseTrait, Support\ViewTrait, Support\MethodTrait;
    use DestroyTrait, CallTrait;

    public function index(Request $request)
    {
        $useCase = $this->getUseCaseClass($action = "index");
        $response = $useCase->handle([
            'request' => $dataRequest = $request->except(['_token']),
            'permissions' => $this->getMethod('permissions') ? $this->permissions() : null,
            'user' => $request->user(),
        ]);

        if (!empty($data = $response['data'])) {
            if (
                !$data instanceof Collection
                && !$data instanceof LengthAwarePaginator
                && !$data instanceof Builder
                && !$data instanceof SupportCollection
                && !is_subclass_of($data, \Illuminate\Database\Eloquent\Model::class)
            ) {
                $msg = 'The method return index is not ' . Collection::class . ' or ';
                $msg .= LengthAwarePaginator::class . ' or ' . Builder::class . ' or ';
                $msg .= 'not extends ' . \Illuminate\Database\Eloquent\Model::class;
                $msg .= ". Sended " . gettype($data);
                throw new Exception(__($msg));
            }

            $data = $this->executeFilteredData($dataRequest, $data);

            if (array_key_exists("sql", $request->all())) {
                die($data->toRawSql());
            }

            if (
                $data instanceof Builder
                || is_subclass_of($data, \Illuminate\Database\Eloquent\Model::class)
            ) {
                $data = $data->paginate($this->getTotalPaginate());
            }

            $response['data'] = $data;
        }

        $response['actions'] = array_merge($response['actions'] ?? [], $this->getActionsTable());
        $response['register'] = $this->linkRegisterInIndex();
        $response['filter'] = array_merge($response['filter'] ?? [], $this->filter());

        return view($this->getNameView($action), $response);
    }

    public function show(Request $request)
    {
        $useCase = $this->getUseCaseClass($action = "show");
        $response = $useCase->handle($request->route()->parameters());
        return view($this->getNameView($action), $response);
    }

    protected function executeFilteredData($dataRequest, $data)
    {
        foreach ($dataRequest as $k => $req) {
            $dados = explode('_', $k);
            if (count($dados) > 1 && !empty($req)) {
                $type = array_shift($dados);
                $table = str_replace('|', '.', implode('_', $dados));
                if ($data) {
                    switch ($type) {
                        case 'like':
                            $newReq = str_replace(' ', '%', $req);
                            $data = $data->where("$table", "like", "%$newReq%");
                            break;

                        case 'lessorequal':
                            $data = $data->where("$table", "<=", "$req");
                            break;

                        case 'moreorequal':
                            $data = $data->where("$table", ">=", "$req");
                            break;

                        case 'equal':
                            $data = $data->where("$table", "=", "$req");
                            break;

                        default:
                            break;
                    }
                }
            }
        }

        return $data;
    }

    protected function getTotalPaginate()
    {
        return 15;
    }

    protected function getActionsTable()
    {
        $objUser = auth()->user();

        $permissions = [];

        if (method_exists($this, 'permissions')) {
            $permissions = $this->permissions();
        }

        $permission = $permissions['index'] ?? null;
        if (
            Route::has(($routeActual = RouteSupport::getRouteActual()) . '.show')
            && (($permission && $objUser->can($permission)) || $permission == null)
        ) {
            $actions["show"] = [
                'action' => function ($obj) use ($routeActual) {
                    return route($routeActual . '.show', $obj->id);
                },
            ];
        }

        $permission = $permissions['edit'] ?? null;
        if (
            Route::has($routeActual . '.edit')
            && (($permission && $objUser->can($permission)) || $permission == null)
        ) {
            $actions["edit"] = [
                'action' => function ($obj) use ($routeActual) {
                    return route($routeActual . '.edit', $obj->id);
                },
            ];
        }

        $permission = $permissions['delete'] ?? null;
        if (
            Route::has($routeActual . '.destroy')
            && (($permission && $objUser->can($permission)) || $permission == null)
        ) {
            $actions["delete"] = [
                'action' => function ($obj) use ($routeActual) {
                    return route($routeActual . '.destroy', $obj->id);
                },
            ];
        }

        return $actions;
    }

    protected function linkRegisterInIndex()
    {
        $objUser = auth()->user();
        $permission = method_exists($this, 'permissions') && !empty($this->permissions()['create'])
            ? $this->permissions()['create']
            : null;

        $register = null;
        if (($permission && $objUser->can($permission)) || $permission == null) {
            $register = Route::has(RouteSupport::getRouteActual() . '.create')
                ? route(RouteSupport::getRouteActual() . '.create', request()->except(['_token']))
                : null;
        }

        return $register;
    }

    protected function filter(): array
    {
        return [];
    }
}