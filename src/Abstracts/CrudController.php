<?php

namespace BRCas\Laravel\Abstracts;

use BRCas\Laravel\Contracts\ServiceContract;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\DB;
use Exception;

abstract class CrudController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected abstract function model();

    protected abstract function service();

    protected abstract function rulesPost(array $data);

    protected abstract function rulesPut(array $data);

    public function index(Request $request)
    {
        if (is_callable($this->service() . '::index')) {
            return $this->service()::index($request->all());
        } else {
            return $this->model()::all();
        }
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

        if (is_callable($this->service() . '::show')) {
            return $this->service()::show($obj);
        } else {
            return $obj;
        }
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
        $service = $this->service();
        $obj = (new $service);
        if (in_array(ServiceContract::class, class_implements($obj)) === false) {
            throw new Exception($this->service() . " don't implements " . ServiceContract::class);
        }

        $dataSend = $this->validate($request, $this->rulesPost($request->all()));
        $obj = DB::transaction(function () use ($dataSend) {
            return $this->service()::store($dataSend);
        });

        return $this->retornoStoreUpdate($obj);
    }

    public function update(Request $request, $id)
    {
        $dataSend = $this->validate($request, $this->rulesPut($request->all()));
        $obj = DB::transaction(function () use ($id, $dataSend) {
            $obj = $this->getObject($id);
            return $this->service()::update($obj, $dataSend);
        });

        return $this->retornoStoreUpdate($obj);
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $obj = $this->getObject($id);
            if (is_callable($this->service() . '::destroy')) {
                return $this->service()::destroy($obj);
            } else {
                $obj->delete();
                return response()->noContent();
            }
        });
    }
}
