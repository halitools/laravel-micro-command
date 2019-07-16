<?php


namespace Halitools\LaravelMicroCommand\Exceptions;


class ValidationException extends \Illuminate\Validation\ValidationException
{
    /**
     * @var array
     */
    private $errors;

    /**
     * ValidationException constructor.
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public function errors()
    {
        return $this->errors;
    }
}