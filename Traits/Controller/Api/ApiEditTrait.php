<?php


namespace Costa\Package\Traits\Controller\Api;


use Costa\Package\Traits\Controller\BaseController;
use Kris\LaravelFormBuilder\FormBuilder;

trait ApiEditTrait
{
    use BaseController;

    public function update($id, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create($this->form());
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $service = app($this->service());
        $service->apiUpdate($id, $form->getFieldValues());
        $obj = $service->find($id);

        $resource = $this->resource();
        return new $resource($obj);

    }

    protected abstract function form();

    protected abstract function resource();
}
