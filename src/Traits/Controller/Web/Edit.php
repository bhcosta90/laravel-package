<?php

namespace BRCas\Laravel\Traits\Controller\Web;

use BRCas\Laravel\Traits\Support\Execute;
use Exception;
use Kris\LaravelFormBuilder\FormBuilder;

trait Edit
{
    use Execute;

    public abstract function service();

    public abstract function form();

    public abstract function editView();

    public abstract function routeBegging();

    public function edit($id, FormBuilder $formBuilder)
    {
        $objService = app($this->service());

        if (!method_exists($objService, 'find')) throw new Exception(__('Method find not found in service'));
        if (!method_exists($objService, 'edit')) throw new Exception(__('Method edit not found in service'));

        $obj = $objService->find($id);

        $form = $formBuilder->create($this->form(), [
            'method' => 'PUT',
            'url' => route($this->routeBegging() . '.update', $obj->id),
            'model' => $obj,
        ])->add('btn', 'submit', [
            "attr" => ['class' => 'btn btn-primary'],
            'label' => __('Update')
        ]);

        return view($this->editView(), compact('form'));
    }

    public function update($id, FormBuilder $formBuilder)
    {
        $objService = app($this->service());

        if (!method_exists($objService, 'find')) throw new Exception(__('Method find not found in service'));
        if (!method_exists($objService, 'edit')) throw new Exception(__('Method edit not found in service'));

        $obj = $objService->find($id);

        return $this->execute(function () use ($obj, $objService, $formBuilder) {

            $objForm = $formBuilder->create($this->form());
            if (!$objForm->isValid()) {
                return redirect()->back()->withErrors($objForm->getErrors())->withInput();
            }

            $data = $objForm->getFieldValues();
            $objService->edit($obj, $data);

            return redirect()->route($this->routeBegging() . ".index");
        });
    }
}