<?php

return [

    'generator' => [
        'api' => [
            'path' => 'app/Api/Modules',
            'namespace' => 'App\Api\Modules',
        ],
        'module' => [
            'path' => 'app/Modules',
            'namespace' => 'App\Modules',
        ],
        'postfix' => 'Module'
    ],

    'exceptions' => [
        "\Illuminate\Validation\ValidationException" => "\Halitools\LaravelMicroCommand\ExceptionResponses\ValidationExceptionResponse"
    ],

    'servers' => [

    ],

    'modules' => [

    ]
];