<?php

namespace BRCas\Laravel\Traits\Controller;

use BRCas\Laravel\Support\RouteSupport;
use Exception;
use Illuminate\Database\Eloquent\{Builder, Collection};
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Route;

trait IndexTrait
{
    use Validation\ValidationService;

    public abstract function table();

    public function getTotalPaginate()
    {
        return env('TOTAL_PAGINATE');
    }

    public function actions()
    {
        $actions = [];

        /**
         * @var \App\Models\User
         */
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

    public function index(Request $request)
    {
        $actionIndex = property_exists($this, 'actionIndex')
            ? $this->actionIndex
            : "index";

        $objService = $this->validateService([$actionIndex]);

        /**
         * @var \App\Models\User
         */
        $objUser = auth()->user();

        $data = $objService->$actionIndex();

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

        $data = $this->filteredData($request, $data);

        if (array_key_exists("sql", $request->all())) {
            die($data->toRawSql());
        }

        if (
            $data instanceof Builder
            || is_subclass_of($data, \Illuminate\Database\Eloquent\Model::class)
        ) {
            $data = $data->paginate($this->getTotalPaginate());
        }

        $table = $this->table();

        $actions = $this->actions();

        $filter = method_exists($this, 'filters') ? $this->filters() : [];

        $permissions = [];
        if (method_exists($this, 'permissions')) {
            $permissions = $this->permissions();
        }

        $permission = $permissions['create'] ?? null;
        $linkRegister = null;
        if (($permission && $objUser->can($permission)) || $permission == null) {
            $linkRegister = Route::has(RouteSupport::getRouteActual() . '.create')
                ? route(RouteSupport::getRouteActual() . '.create')
                : null;
        }

        $viewPackage = property_exists($this, 'viewPackage')
            ? $this->viewPackage . ":"
            : "";

        return view(
            $viewPackage . $request->route()->getName(),
            compact(
                'data',
                'table',
                'actions',
                'filter',
                'linkRegister'
            )
        );
    }

    protected function filteredData($request, $data)
    {
        foreach ($request->except(['_token']) as $k => $req) {
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
}
