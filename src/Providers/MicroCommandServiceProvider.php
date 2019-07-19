<?php


namespace Halitools\LaravelMicroCommand\Providers;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Halitools\LaravelMicroCommand\Console\MakeModuleClassCommand;
use Halitools\LaravelMicroCommand\Console\MakeModuleCommand;
use Halitools\LaravelMicroCommand\Console\MakeModuleInterfaceCommand;
use Halitools\LaravelMicroCommand\Exceptions\OauthException;
use Halitools\LaravelMicroCommand\Helpers\GuzzleOptionsHelper;
use Halitools\MicroCommand\Request\MicroService;
use Halitools\MicroCommand\Request\RemoteMicroService;
use Halitools\MicroCommand\Response\ExceptionResponseFactory;
use Illuminate\Support\ServiceProvider;


class MicroCommandServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/micro-command.php', 'micro-command');


        $this->app->resolving(ExceptionResponseFactory::class, function(ExceptionResponseFactory $factory) {
            foreach (config('micro-command.exceptions', []) as $exceptionClass => $exceptionResponse) {
                $factory->addCustomError($exceptionClass, $exceptionResponse);
            }
        });

        $this->app->resolving(function($module) {
            if (!is_subclass_of($module, MicroService::class)) {
                return $module;
            }
            /** @var MicroService $module */
            $name = $module->getName();
            $serviceConfig = config('micro-command.modules.' . $name);
            if (empty($serviceConfig)) {
                return $module;
            }
            $this->setMicroServiceConfig($module, $serviceConfig);

            if (empty($serviceConfig['server']) || !is_subclass_of($module, RemoteMicroService::class)) {
                return $module;
            }
            $this->setClient($module, config('micro-command.servers.' . $serviceConfig['server'], []));
            return $module;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            MakeModuleCommand::class,
            MakeModuleInterfaceCommand::class,
            MakeModuleClassCommand::class
        ]);
        $this->publishes([
            __DIR__.'/../../config/micro-command.php' => config_path('micro-command.php'),
        ], 'config');
    }

    private function setMicroServiceConfig(MicroService $microService, array $config)
    {
        foreach (['implements', 'namespace', 'implementations'] as $param) {
            if (!empty($config[$param])) {
                $microService->{'set' . ucfirst($param)}($config[$param]);
            }
        }
    }

    /**
     * @param RemoteMicroService $module
     * @param array $clientConfig
     * @throws OauthException
     */
    private function setClient(RemoteMicroService $module, array $clientConfig)
    {
        $module->setUri(new Uri($clientConfig['uri'] ?? ''));
        if (!empty($clientConfig['config']) || !empty($clientConfig['oauth']) || !empty($clientConfig['middleware'])) {
            $guzzleOptionsHelper = new GuzzleOptionsHelper($clientConfig);
            $client = app(Client::class, ['config' => $guzzleOptionsHelper->getOptions()]);
            $module->setClient($client);
        }
    }
}