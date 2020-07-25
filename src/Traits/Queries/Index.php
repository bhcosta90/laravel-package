<?php

namespace BRCas\Laravel\Traits\Queries;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Route;

trait Index
{
    protected function getTotalPaginate()
    {
        return request()->input('pageSize', 30);
    }

    public function list(Request $request)
    {
        $dataSend = $request->all();

        if (method_exists($this, 'rulesIndex')) {
            $dataSend = $request->validate($this->rulesIndex());
        }

        $routeName = Route::currentRouteName();
        $model = $this->model();
        $obj = new $model;

        if ($request->get('filter') == '') {
            $routeReplacePoint = str_replace('.', ' ', $routeName);
            $routeTransformCamelCase = ucwords($routeReplacePoint);
            $routeTransformFunction = str_replace(' ', '', $routeTransformCamelCase);
            $routeTransformFunctionName = "query$routeTransformFunction";
            if ($request->get('router') == true) {
                print $routeTransformFunctionName;
                exit;
            }
        } else {
            $routeTransformFunctionName = "query" . $request->get('filter');
        }

        if ($request->get('route') == true) {
            print $routeTransformFunctionName and exit;
        }

        if (method_exists($obj, $routeTransformFunctionName)) {
            $obj = $obj->$routeTransformFunctionName($request->all());
        }

        foreach ($dataSend as $k => $data) {
            $dados = explode('_', $k);
            if (count($dados) > 1) {
                $type = array_shift($dados);
                $tabela = str_replace('|', '.', array_shift($dados));
                if ($data) {
                    switch ($type) {
                        case 'like':
                            $obj = $obj->where("$tabela", "like", "$data%");
                            break;

                        case 'equal':
                            $obj = $obj->where("$tabela", "=", "$data");
                            break;
                    }
                }
            }
        }

        if ($request->get('sql') == true) {
            print $obj->toRawSql() and exit;
        }

        $data = !$this->getTotalPaginate()
            ? $obj->all()
            : $obj->paginate(
                $this->getTotalPaginate(),
                ["*"],
                'page',
                (int) (request()->input('pageIndex') ?? request()->input('page', 0)) + 1
            );

        $resourceCollectionClass = $this->resourceCollection();
        $refClass = new \ReflectionClass($resourceCollectionClass);
        return $refClass->isSubclassOf(ResourceCollection::class)
            ? new $resourceCollectionClass($data)
            : $resourceCollectionClass::collection($data);
    }

    protected abstract function model();

    protected abstract function resourceCollection();

    protected abstract function resource();
}
