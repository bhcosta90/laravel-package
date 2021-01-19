<?php

namespace Costa\Package\Traits\Controllers;

use Costa\Package\Exceptions\CustomException;
use Costa\Package\Traits\BaseTrait;;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

trait EditTrait
{
    use BaseTrait, UpdateTrait;

    /**
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     * @throws CustomException
     */
    public function edit(FormBuilder $formBuilder, Request $request, $id)
    {
        try {
            $service = app($this->service());
            $function = $this->functionEdit() ?: $this->getNameFunction($this->getUcWords($this->getNameRoute())).'Show';
            if (!method_exists($service, $function)) {
                throw new CustomException(__("Method :function do not exist in service :service", [
                    'function' => $function,
                    'service' => get_class($service)
                ]));
            }

            $obj = $service->$function($id);

            $form = $formBuilder->create($this->form(), [
                'method' => 'PUT',
                'model' => $obj,
                'attr' => [
                    'id' => 'formDefault'
                ],
                'url' => route($this->getNameRoute() . '.update', $id),
            ], $request->route()->parameters())->add('btn', 'submit', [
                "attr" => [
                    'class' => 'btn btn-primary',
                    'id' => 'btnForm'
                ],
                'label' => __('Edit')
            ]);
            return view($this->getView().'.edit', ['form' => $form] + $this->returnEditAction());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     */
    protected function returnEditAction(): array
    {
        return [];
    }

    /**
     * @return null
     */
    public function functionEdit()
    {
        return null;
    }
}
