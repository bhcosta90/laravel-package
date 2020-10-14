<?php


namespace Package\Traits\Controller;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Kris\LaravelFormBuilder\FormBuilder;
use Package\Traits\Actions\DestroyTrait;
use Package\Traits\Actions\StoreTrait;
use Package\Traits\Actions\UpdateTrait;
use Package\Traits\Query\IndexTrait;

trait ResourceTrait
{
    use IndexTrait, DestroyTrait, StoreTrait, UpdateTrait;

    protected $obj;

    public function titleIndex()
    {
        return 'Relatório';
    }

    public function titleCreate()
    {
        return 'Cadastro';
    }

    public function titleEdit()
    {
        return 'Alteração';
    }

    /**
     * @return mixed
     */
    public abstract function model();

    /**
     * @return mixed
     */
    public abstract function routeResource();

    /**
     * @return mixed
     */
    public abstract function formStore();

    /**
     * @return mixed
     */
    public abstract function formUpdate();

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $model = $this->model();
        $result = new $model;

        $routes = collect(explode('.', Route::currentRouteName()))->last();
        if(method_exists($this, $routes . "Query")){
            $nameFilter = $routes . "Query";
            $result = $this->$nameFilter($result);
        }

        $data = $this->query($result, $request->all());

        $table = null;
        if (method_exists($this, 'getTable')) {
            $table = $this->getTable();
        }

        $actions = [];
        if (method_exists($this, 'getActions')) {
            $actions = $this->getActions();
        }

        $title = $this->titleIndex();
        $titleCreate = $this->titleCreate();
        $filter = $this->getFilter();
        $filter_none = "none";

        if(is_array($filter)) {
            foreach ($filter as $key => $value) {
                if (substr($key, 0, 7) != 'request' && request($key) != '') {
                    $filter_none = "block";
                    break;
                }
            }
        }

        $new = $this->routeCreate();


        return view('default.list', compact('data', 'table', 'actions', 'title', 'new', 'titleCreate', 'filter', 'filter_none'));
    }

    public function getTable()
    {
        return null;
    }

    public function getActions()
    {
        return null;
    }

    public function getFilter()
    {
        return null;
    }

    public function routeCreate(){
        return route($this->routeResource() . '.create');
    }

    /**
     * @param FormBuilder $formBuilder
     * @return Application|Factory|View
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create($this->formStore(), [
            'method' => 'POST',
            'url' => route($this->routeResource() . '.store')
        ])->add('btn', 'submit', [
            "attr" => ['class' => 'btn btn-primary'],
            'label' => __('Cadastrar')
        ]);

        $title = $this->titleCreate();

        return view('default.form', compact('form', 'title'));
    }

    /**
     * @param FormBuilder $formBuilder
     * @param $id
     * @return Application|Factory|View
     */
    public function edit(FormBuilder $formBuilder, $id)
    {
        $model = $this->model();
        $obj = $model::find($id);

        $form = $formBuilder->create($this->formUpdate(), [
            'method' => 'PUT',
            'url' => route($this->routeResource() . '.update', $id),
            'model' => $obj
        ])->add('btn', 'submit', [
            "attr" => ['class' => 'btn btn-primary'],
            'label' => __('Editar')
        ]);

        $title = $this->titleEdit();

        return view('default.form', compact('form', 'title'));
    }
}
