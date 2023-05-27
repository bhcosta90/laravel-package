<?php

namespace BRCas\Laravel\Abstracts\Traits;

use BRCas\Laravel\Support\RouteSupport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\{Builder, Collection};
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;

trait IndexTrait
{
    use Validation\ServiceTrait, ViewTrait;

    protected abstract function table();

    private function executeTable(Request $request, string $action)
    {
        $objService = $this->validateService([$action]);

        $data = $objService->$action();

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

        $data = $this->executeFilteredData($request, $data);

        if (array_key_exists("sql", $request->all())) {
            die($data->toRawSql());
        }

        if (
            $data instanceof Builder
            || is_subclass_of($data, \Illuminate\Database\Eloquent\Model::class)
        ) {
            $data = $data->paginate($this->getTotalPaginate());
        }

        $register = $this->linkRegisterInIndex();
        $table = $this->table();
        $actions = $this->getActionsTable();
        $filter = $this->getFilter();

        return view(
            $this->getView('index'),
            compact(
                'data',
                'table',
                'actions',
                'filter',
                'register'
            )
        );
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

    protected function executeFilteredData(Request $request, $data)
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

    protected function getTotalPaginate(): int
    {
        return env('TOTAL_PAGINATE', 20);
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
                ? route(RouteSupport::getRouteActual() . '.create')
                : null;
        }

        return $register;
    }

    protected function getFilter()
    {
        return [];
    }
}
