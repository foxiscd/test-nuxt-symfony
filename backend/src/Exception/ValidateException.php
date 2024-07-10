<?php

namespace App\Exception;

class ValidateException extends \Exception
{
    private array $errors = [];

    public function __construct($errors)
    {
        $this->errors = $errors;
        parent::__construct($message = 'Validation failed', $code = 0, $previous = null);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}