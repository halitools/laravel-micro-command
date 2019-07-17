<?php


namespace Halitools\LaravelMicroCommand\Console;


use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

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

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $path = base_path(config('micro-command.generator.api.path'));
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $path.'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Get the root namespace for the class.
     *
* @return string
*/
    protected function rootNamespace()
    {
        return config('micro-command.generator.api.namespace', $this->laravel->getNamespace());
    }

    protected function buildClass($name)
    {
        $replace = [
            'DummyModuleName' => $this->argument('name')
        ];
        return str_replace(array_keys($replace), array_values($replace), parent::buildClass($name));
    }

    protected function getNameInput()
    {
        $nameInput =  parent::getNameInput();
        return $nameInput . '/' . $nameInput . config('micro-command.generator.postfix') . 'Interface';
    }
}