<?php

namespace BRCas\Laravel\Traits\Controller\Web;

use Exception;
use Illuminate\Database\Eloquent\{Builder, Collection};
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Route;

trait Index
{
    public abstract function service();

    public abstract function table();

    public abstract function indexView();

    public abstract function routeBegging();

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
        if (method_exists($this, 'permissions') && method_exists($this, '__construct')) $permissions = $this->permissions();

        $permission = $permissions['index'] ?? null;
        if (Route::has($this->routeBegging() . '.show')) {
            if (($permission && $objUser->can($permission)) || $permission == null) {
                $actions["show"] = [
                    'action' => function ($obj) {
                        return route($this->routeBegging() . '.show', $obj->id);
                    },
                ];
            }
        }

        $permission = $permissions['edit'] ?? null;
        if (Route::has($this->routeBegging() . '.edit') && Route::has($this->routeBegging() . '.update')) {
            if (($permission && $objUser->can($permission)) || $permission == null) {
                $actions["edit"] = [
                    'action' => function ($obj) {
                        return route($this->routeBegging() . '.edit', $obj->id);
                    },
                ];
            }
        }

        $permission = $permissions['delete'] ?? null;
        if (Route::has($this->routeBegging() . '.destroy')) {
            if (($permission && $objUser->can($permission)) || $permission == null) {
                $actions["delete"] = [
                    'action' => function ($obj) {
                        return route($this->routeBegging() . '.destroy', $obj->id);
                    },
                ];
            }
        }

        return $actions;
    }

    public function index(Request $request)
    {
        $objService = app($this->service());

        if (!in_array(\BRCas\Laravel\Contracts\Index::class, class_implements($objService)))
            throw new Exception(__('Interface '.\BRCas\Laravel\Contracts\Index::class.' not found in service'));

        /**
         * @var \App\Models\User
         */
        $objUser = auth()->user();

        $data = $objService->index();

        if (!$data instanceof Collection
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

        foreach ($request->except(['_token']) as $k => $req) {
            $dados = explode('_', $k);
            if (count($dados) > 1 && !empty($req)) {
                $type = array_shift($dados);
                $tabela = str_replace('|', '.', implode('_', $dados));
                if ($data) {
                    switch ($type) {
                        case 'like':
                            $data = $data->where("$tabela", "like", "$req%");
                            break;

                        case 'lessorequal':
                            $data = $data->where("$tabela", "<=", "$req");
                            break;

                        case 'moreorequal':
                            $data = $data->where("$tabela", ">=", "$req");
                            break;

                        case 'equal':
                            $data = $data->where("$tabela", "=", "$req");
                            break;
                    }
                }
            }
        }

        if (array_key_exists("sql", $request->all())) {
            print $data->toRawSql() and exit;
        }

        if ($data instanceof Builder || is_subclass_of($data, \Illuminate\Database\Eloquent\Model::class)) {
            $data = $data->paginate($this->getTotalPaginate());
        }

        $table = $this->table();

        $actions = $this->actions();

        $filter = method_exists($this, 'filters') ? $this->filters() : [];

        $permissions = [];
        if (method_exists($this, 'permissions') && method_exists($this, '__construct')) $permissions = $this->permissions();

        $permission = $permissions['create'] ?? null;;
        $linkRegister = null;
        if (($permission && $objUser->can($permission)) || $permission == null) {
            $linkRegister = Route::has($this->routeBegging() . '.create') ? route($this->routeBegging() . '.create') : null;
        }

        return view($this->indexView(), compact('data', 'table', 'actions', 'filter', 'linkRegister'));
    }
}
