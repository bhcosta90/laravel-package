<?php

namespace BRCas\Laravel\Abstracts\Traits;

use BRCas\Laravel\Support\FormSupport;
use BRCas\Laravel\Support\RouteSupport;

trait FormTrait
{
    use Validation\MethodTrait;

    public function runForm($action, $send, $buttonSubmit, $attributes = [])
    {
        $formSupport = app(FormSupport::class);

        $form = str()->camel($action . " Form");
        $this->validateMethod([$form]);

        $form = $formSupport->run(
            $this->$form(),
            route(RouteSupport::getRouteActual() . '.' . $send, request()->route()->parameters()),
            $attributes['model'] ?? null,
            [
                'submit' => $buttonSubmit
            ]
        );

        return $form;
    }
}
