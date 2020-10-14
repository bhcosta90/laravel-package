<?php


namespace BRCas\Laravel\Traits\Actions;


use BRCas\Laravel\Traits\Query\ExecuteTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

trait UpdateTrait
{
    use ExecuteTrait, Redirect;

    /**
     * @return mixed
     */
    public abstract function routeResource();

    /**
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(FormBuilder $formBuilder, Request $request, $id)
    {
        $objForm = $formBuilder->create($this->formUpdate());
        if (!$objForm->isValid()) {
            return redirect()->back()->withErrors($objForm->getErrors())->withInput();
        }

        return $this->execute(function () use ($objForm, $request, $id) {
            $model = $this->model();
            $obj = (new $model)->find($id);
            $data = $request->all(['account_id', 'account_name']) + $objForm->getFieldValues();
            $this->executeUpdate($obj, $data);

            if (!request()->isJson()) {
                request()->session()->flash("success", __("Registro atualizado com sucesso"));
                return redirect($this->routeIndex());
            }
        });
    }

    /**
     * @return mixed
     */
    public abstract function formUpdate();

    /**
     * @return mixed
     */
    public abstract function model();

    public function executeUpdate($obj, $data)
    {
        $obj->update($data);
        return $obj;
    }
}
