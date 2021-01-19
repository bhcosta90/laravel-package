<?php

namespace Costa\Package\Traits\Controllers;

use Costa\Package\Exceptions\WebException;
use Costa\Package\Traits\BaseTrait;
use Costa\Package\Util\ExecuteAction;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

trait CreateTrait
{
    use BaseTrait, StoreTrait;

    /**
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return Application|Factory|View
     * @throws WebException
     */
    public function create(FormBuilder $formBuilder, Request $request)
    {
        try {
            $form = $formBuilder->create($this->form(), [
                'method' => 'POST',
                'attr' => [
                    'id' => 'formDefault'
                ],
                'url' => route($this->getNameRoute() . '.store'),
            ], $request->route()->parameters())->add('btn', 'submit', [
                "attr" => [
                    'class' => 'btn btn-primary',
                    'id' => 'btnForm'
                ],
                'label' => __('Create')
            ]);
            return view($this->getView().'.create', ['form' => $form] + $this->returnCreateAction());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     */
    protected function returnCreateAction(): array
    {
        return [];
    }

    /**
     * @throws WebException
     * @return string
     */
    public function form(): string
    {
        throw new WebException('Form do not implemented');
    }
}
