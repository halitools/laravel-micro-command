<?php


namespace Halitools\LaravelMicroCommand\Console;


use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakeModuleClassCommand extends GeneratorCommand
{

    protected $name = 'micro:make:class';

    protected $description = 'Make a new module microservice';

    protected $type = 'Module microservice';

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $path = base_path($this->getConfig()['path']);
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $path.'/'.str_replace('\\', '/', $name).'.php';
    }

    protected function buildClass($name)
    {
        $dummyUseInterface = '';
        if ($this->option('type') == 'local') {
            $interface = config('micro-command.generator.api.namespace') . '/' . $this->getNameInput() . 'Interface';
            $dummyUseInterface = 'use ' . str_replace('/', '\\', $interface) . ';';
        }

        return str_replace('DummyUseInterface', $dummyUseInterface, parent::buildClass($name));
    }


    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->getConfig()['namespace'] ?? '';
    }
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/'. $this->option('type') .'_microservice.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

    protected function getNameInput()
    {
        $nameInput =  parent::getNameInput();
        return $nameInput . '/' . $nameInput . config('micro-command.generator.postfix');
    }

    public function getOptions()
    {
        return [
            ['type', 't', InputArgument::OPTIONAL, 'remote or local microservice implementation', 'remote']
        ];
    }

    private function getConfig()
    {
        if ($this->option('type') == 'local') {
            return config('micro-command.generator.module');
        }
        return config('micro-command.generator.api');
    }

}