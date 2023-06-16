<?php

namespace BRCas\Laravel\Traits\Controller\Support;

use Illuminate\Validation\ValidationException;
use Kris\LaravelFormBuilder\FormBuilder;

trait FormTrait
{
    protected function formGenerate(string $form, string $route, array $attributes = [])
    {
        $formBuilder = app(FormBuilder::class);

        $generateForm = $formBuilder->create($form, [
            'method' => $attributes['method'] ?? (empty($attributes['model']) ? "POST" : "PUT"),
            'url' => $route,
            'model' => $attributes['model'] ?? [],
            'class' => 'laravel-form-builder',
        ], $attributes['data'] ?? []);

        $generateForm->add('button_action', 'submit', [
            "attr" => [
                'class' => 'btn btn-primary btn-action',
                'data-label' => $btn = __($attributes['submit'] ?? (empty($attributes['model']) ? "New" : "Update")),
                'value' => 'button_action'
            ],
            'label' => $btn,
        ]);

        return $generateForm;
    }

    protected function formData($form)
    {
        $formBuilder = app(FormBuilder::class);
        $dataForm = $formBuilder->create($form);

        if (!$dataForm->isValid()) {
            throw ValidationException::withMessages($dataForm->getErrors());
        }
        return $dataForm->getFieldValues();
    }
}
