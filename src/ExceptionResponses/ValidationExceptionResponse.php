<?php


namespace Halitools\LaravelMicroCommand\ExceptionResponses;


use Halitools\LaravelMicroCommand\Exceptions\ValidationException as RemoteValidationException;
use Halitools\MicroCommand\Response\ExceptionResponse;
use Halitools\MicroCommand\Response\ExceptionResponseInterface;
use Illuminate\Validation\ValidationException;

class ValidationExceptionResponse extends ExceptionResponse
{

    public static function build(\Exception $exception): ExceptionResponseInterface
    {
        /** @var ValidationException $exception */
        return new static([
            'errors' => $exception->errors(),
            'status' => $exception->status,
        ]);
    }

    public function getException()
    {
        return new RemoteValidationException($this->data['errors']);
    }
}