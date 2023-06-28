<?php

declare(strict_types=1);

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
                'id' => $attributes['key'] ?? 'form-builder-' . sha1(str()->uuid()),
                'class' => $attributes['key'] ?? 'laravel-form-builder'
            ]
        ], $attributes['data'] ?? []);

        $btnSubmit = __($attributes['submit'] ?? ($model ? "Atualizar" : "Cadastrar"));

        $formRun->add('button_action', 'submit', [
            "attr" => [
                'class' => 'btn btn-primary btn-action',
                'data-label' => $btnSubmit,
                'value' => 'button_action'
            ],
            'label' => $btnSubmit,
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
