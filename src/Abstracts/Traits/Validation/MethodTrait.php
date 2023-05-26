<?php

namespace BRCas\Laravel\Abstracts\Traits\Validation;

use Exception;

trait MethodTrait
{
    protected function validateMethod(array $methods)
    {
        foreach ($methods as $method) {
            if (!$this->getMethod($method)) {
                throw new Exception(__('Method ' . $method . ' not found this class'));
            }
        }
    }

    public function getMethod($method)
    {
        return method_exists($this, $method) ? $method : null;
    }
}
