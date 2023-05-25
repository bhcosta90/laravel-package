<?php

namespace BRCas\Laravel\Support;

use Illuminate\Validation\ValidationException;
use Kris\LaravelFormBuilder\FormBuilder;

class FormSupport
{
    public function __construct(
        private FormBuilder $formBuilder,
    ) {
        //
    }

    public function run(
        string  $form,
        string  $action,
        object|array|null $model = null,
        array $attributes = [],
    ) {
        $formRun = $this->formBuilder->create($form, [
            'method' => $attributes['method'] ?? ($model ? "PUT" : "POST"),
            'url' => $action,
            'model' => $model,
            'attr' => [
                'id' => 'form-builder-'.sha1(str()->uuid()),
            ]
        ], $attributes['data'] ?? []);

        $formRun->add('button_action', 'submit', [
            "attr" => [
                'class' => 'btn btn-primary btn-action',
                'data-label' => __($attributes['submit']),
                'value' => 'button_action'
            ],
            'label' => __($attributes['submit'] ?? ($model ? "Update" : "Register")),
        ]);

        return $formRun;
    }

    public function data(string $form, array $attributes = [])
    {
        $formRun = $this->formBuilder->create($form, [], $attributes['data'] ?? []);

        if (!$formRun->isValid()) {
            throw ValidationException::withMessages($formRun->getErrors());
        }

        return $formRun->getFieldValues();
    }
}
