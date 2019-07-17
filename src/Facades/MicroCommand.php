<?php


namespace Halitools\LaravelMicroCommand\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class MicroCommand
 * @package Halitools\LaravelMicroCommand\Facades
 * @method static route(string $path, array $options = [])
 */
class MicroCommand extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'microcommand';
    }

}