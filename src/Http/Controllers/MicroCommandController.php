<?php


namespace Halitools\LaravelMicroCommand\Http\Controllers;


use Halitools\MicroCommand\Response\CommandHandler;
use Illuminate\Http\Request;

class MicroCommandController
{
    public function handle(CommandHandler $commandHandler, Request $request)
    {
        return $commandHandler->handle($request->getContent());
    }
}