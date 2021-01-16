<?php


namespace Costa\Package\Exceptions;

use Exception;
use Throwable;

class CustomException extends Exception
{
    private string $typeError;

    public function __construct($message = "", $code = 0, $typeError = 'error', Throwable $previous = null)
    {
        $this->typeError = $typeError;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed|string
     */
    public function getTypeError(): string
    {
        return $this->typeError;
    }
}
