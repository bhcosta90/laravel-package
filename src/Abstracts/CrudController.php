<?php

namespace BRCas\Laravel\Abstracts;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class CrudController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $paginateSize = 15;

    protected abstract function model();

    protected abstract function resource();

    protected abstract function resourceCollection();

    protected abstract function rulesPost(array $data);

    protected abstract function rulesPut(array $data);

    public function index(Request $request)
    {
        try{
            $dataSend = $request->all();

            if(method_exists($this, 'rulesIndex')){
                $dataSend = $this->validate($request, $this->rulesIndex());
            }

            $routeName = Route::currentRouteName();
            if ($request->get('route') == true) {
                print $routeName and exit;
            }

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

            if (method_exists($obj, $routeTransformFunctionName)) {
                $obj = $obj->$routeTransformFunctionName($request->all());
            }

            foreach ($dataSend as $k => $data) {
                $dados = explode('_', $k);
                if (count($dados) > 1) {
                    $type = array_shift($dados);
                    $tabela = array_shift($dados);
                    $field = implode('_', $dados);
                    if ($data) {
                        switch ($type) {
                            case 'like':
                                $obj = $obj->where("$tabela.$field", "like", "$data");
                                break;

                            case 'equal':
                                $obj = $obj->where("$tabela.$field", "=", "$data");
                                break;
                        }
                    }
                }
            }

            if ($request->get('sql') == true) {
                print $obj->toRawSql() and exit;
            }

            $data = !$this->paginateSize
                ? $obj->all()
                : $obj->paginate($this->paginateSize);

            $resourceCollectionClass = $this->resourceCollection();
            $refClass = new \ReflectionClass($resourceCollectionClass);
            return $refClass->isSubclassOf(ResourceCollection::class)
                ? new $resourceCollectionClass($data)
                : $resourceCollectionClass::collection($data);
        }catch(\Exception $e){
            return [
                'status' => $e->getCode() ?: 500,
                'msg' => $e->getMessage()
            ];
        }
    }

    private function getObject($id)
    {
        $model = $this->model();
        $keyName = (new $model)->getRouteKeyName();
        $table = (new $model)->getTable();

        return $this->model()::where("$table.$keyName", $id)->firstOrFail();
    }

    public function show($id)
    {
        $obj = $this->getObject($id);

        $resource = $this->resource();
        return new $resource($obj);
    }

    private function retornoStoreUpdate($obj)
    {
        switch (get_class($obj)) {
            case 'Illuminate\Http\JsonResponse':
                return $obj;
                break;
            default:
                $obj->refresh();
                return $obj;
        }
    }

    public function store(Request $request)
    {
        $dataSend = $this->validate($request, $this->rulesPost($request->all()));
        $obj = DB::transaction(function () use ($dataSend) {
            $obj = $this->model()::create($dataSend);
            return $obj;
        });

        $resource = $this->resource();
        return new $resource($this->retornoStoreUpdate($obj));
    }

    public function update(Request $request, $id)
    {
        $dataSend = $this->validate($request, $this->rulesPut($request->all()));
        $obj = DB::transaction(function () use ($id, $dataSend) {
            $obj = $this->getObject($id);
            $obj->update($dataSend);
            $obj->save();
            return $obj;
        });

        $resource = $this->resource();
        return new $resource($this->retornoStoreUpdate($obj));
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $obj = $this->getObject($id);
            $obj->delete();
            return response()->noContent();
        });
    }
}
