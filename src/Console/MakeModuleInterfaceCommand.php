<?php


namespace Halitools\LaravelMicroCommand\Console;


use Illuminate\Console\GeneratorCommand;

class MakeModuleInterfaceCommand extends GeneratorCommand
{

    protected $name = 'micro:make:interface';

    protected $description = 'Make a new module interface';

    protected $type = 'ModuleInterface';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/module_interface.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return config('micro-command.generator.api.namespace');
    }

    protected function buildClass($name)
    {
        $replace = [
            'DummyModuleName' => $name
        ];
        return str_replace(array_keys($replace), array_values($replace), parent::buildClass($name));
    }

    protected function getNameInput()
    {
        $nameInput =  parent::getNameInput();
        return $nameInput . '/' . $nameInput . config('micro-command.generator.postfix') . 'Interface';
    }
}