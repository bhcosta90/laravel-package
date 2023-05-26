<?php

namespace BRCas\Laravel\Abstracts\Traits\Validation;

use Exception;

trait ServiceTrait
{
    public abstract function service();

    protected function validateService(array $methods)
    {
        $objService = app($this->service());

        foreach ($methods as $method) {
            if (!method_exists($objService, $method)) {
                throw new Exception(__('Method ' . $method . ' not found in service'));
            }
        }

        return $objService;
    }
}
