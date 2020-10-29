<?php

namespace BRCas\Laravel\Traits\Controller\Web;

use BRCas\Laravel\Traits\Support\Execute;
use Exception;
use Kris\LaravelFormBuilder\FormBuilder;

trait Create
{
    use Execute;

    public abstract function service();

    public abstract function form();

    public abstract function createView();

    public abstract function routeBegging();

    public function create(FormBuilder $formBuilder)
    {
        $objService = app($this->service());

        if (!in_array(\BRCas\Laravel\Contracts\Create::class, class_implements($objService)))
            throw new Exception(__('Interface '.\BRCas\Laravel\Contracts\Create::class.' not found in service'));

        $form = $formBuilder->create($this->form(), [
            'method' => 'POST',
            'url' => route($this->routeBegging() . '.store'),
        ])->add('btn', 'submit', [
            "attr" => ['class' => 'btn btn-primary'],
            'label' => __('New')
        ]);

        return view($this->createView(), compact('form'));
    }

    public function store(FormBuilder $formBuilder)
    {
        $objService = app($this->service());

        if (!method_exists($objService, 'create')) throw new Exception(__('Method create not found in service'));

        return $this->execute(function () use ($objService, $formBuilder) {
            $objForm = $formBuilder->create($this->form());
            if (!$objForm->isValid()) {
                return redirect()->back()->withErrors($objForm->getErrors())->withInput();
            }

            $data = $objForm->getFieldValues();
            $obj = $objService->create($data);

            return method_exists($this, 'redirectCreate') == false ?
                redirect()->route($this->routeBegging() . ".index") : $this->redirectCreate($obj);
        });
    }
}
