<?php


namespace Halitools\LaravelMicroCommand;


use Halitools\LaravelMicroCommand\Http\Controllers\MicroCommandController;
use Illuminate\Support\Facades\Route;

class MicroCommand
{

    public function route(string $path, array $options = [])
    {
        Route::group($options, function() use ($path) {
            Route::post($path, MicroCommandController::class . '@handle');
        });
    }

    public function client()
    {
        
    }

}