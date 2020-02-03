<?php

namespace BRCas\Laravel\Abstracts;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\DB;
use Exception;
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
        $resource = $this->resource();
        $data = !$this->paginateSize 
            ? $request->all() 
            : $this->model()::paginate($this->paginateSize);

        $resourceCollectionClass = $this->resourceCollection();
        $refClass = new \ReflectionClass($resourceCollectionClass);
        return $refClass->isSubclassOf(ResourceCollection::class) 
            ? new $resourceCollectionClass($data) 
            : $resourceCollectionClass::collection($data);
    }

    private function getObject($id)
    {
        $model = $this->model();
        $keyName = (new $model)->getRouteKeyName();
        return $this->model()::where($keyName, $id)->firstOrFail();
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
