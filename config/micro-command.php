<?php

use Halitools\LaravelMicroCommand\ExceptionResponses\ValidationExceptionResponse;
use Illuminate\Validation\ValidationException;

return [

    'path' => 'app/Modules',

    'exceptions' => [
        ValidationException::class => ValidationExceptionResponse::class
    ],

    'clients' => [

    ],

    'services' => [

    ]
];