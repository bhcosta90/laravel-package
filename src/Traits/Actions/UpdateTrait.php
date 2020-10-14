<?php


namespace BRCas\Laravel\Traits\Actions;


use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;
use Package\Traits\Query\ExecuteTrait;

trait UpdateTrait
{
    use ExecuteTrait, Redirect;

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
    public abstract function formUpdate();

    public function executeUpdate($obj, $data)
    {
        $obj->update($data);
        return $obj;
    }

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
}
