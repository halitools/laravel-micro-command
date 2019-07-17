<?php


namespace Halitools\LaravelMicroCommand\Console;


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class MakeModuleCommand extends Command
{
    protected $name = 'micro:make:module';

    public function handle()
    {
        $name = $this->argument('name');
        $this->call('micro:make:interface', ['name' => $name]);
        $this->call('micro:make:class', ['name' => $name]);
        if (!$this->option('api')) {
            $this->call('micro:make:class', ['name' => $name, '--type' => 'local']);
        }
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Name of the generated module']
        ];
    }

    protected function getOptions()
    {
        return [
            ['api', 'a', null, 'Create only interface and remote client']
        ];
    }

}