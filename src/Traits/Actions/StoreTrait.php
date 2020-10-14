<?php


namespace BRCas\Laravel\Traits\Actions;


use BRCas\Laravel\Traits\Query\ExecuteTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

trait StoreTrait
{
    use ExecuteTrait, Redirect;

    /**
     * @return mixed
     */
    public abstract function routeResource();

    /**
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(FormBuilder $formBuilder, Request $request)
    {
        $objForm = $formBuilder->create($this->formStore());
        if (!$objForm->isValid()) {
            return redirect()->back()->withErrors($objForm->getErrors())->withInput();
        }

        return $this->execute(function () use ($objForm, $request) {
            $model = $this->model();
            $obj = new $model;
            $data = $request->all(['account_id', 'account_name']) + $objForm->getFieldValues();
            $this->executeStore($obj, $data);

            if (!request()->isJson()) {
                request()->session()->flash("success", __("Registro cadastrado com sucesso"));
                return redirect($this->routeIndex());
            }
        });
    }

    /**
     * @return mixed
     */
    public abstract function formStore();

    /**
     * @return mixed
     */
    public abstract function model();

    public function executeStore($obj, $data)
    {
        return $obj->create($data);
    }
}
