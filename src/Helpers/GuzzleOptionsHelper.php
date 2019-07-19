<?php


namespace Halitools\LaravelMicroCommand\Helpers;


use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Halitools\LaravelMicroCommand\Exceptions\OauthException;
use kamermans\OAuth2\GrantType\ClientCredentials;
use kamermans\OAuth2\GrantType\PasswordCredentials;
use kamermans\OAuth2\OAuth2Middleware;

class GuzzleOptionsHelper
{

    private $clientConfig = [];

    private $options;

    /**
     * GuzzleOptionsHelper constructor.
     * @param array $clientConfig
     */
    public function __construct(array $clientConfig)
    {
        $this->clientConfig = $clientConfig;
    }

    public function getOptions(): array
    {
        return $this->setConfig()->setOauth()->setMiddleware()->options;
    }

    private function setConfig(): self
    {
        $this->options = $this->clientConfig['config'] ?? [];
        return $this;
    }

    private function setOauth(): self
    {
        if (!empty($this->clientConfig['oauth'])) {
            $this->options['auth'] = 'oauth';
            $this->getHandlerStack()->push($this->createOAuthMiddleware($this->clientConfig['oauth']), 'oauth');
        }
        return $this;
    }

    private function setMiddleware(): self
    {
        foreach ($this->clientConfig['middleware'] as $key => $middleware) {
            $this->getHandlerStack()->push(new $middleware(), !is_numeric($key)? $key : '');
        }
        return $this;
    }

    private function getHandlerStack(): HandlerStack
    {
        if (empty($this->options['handler'])) {
            $this->options['handler'] = HandlerStack::create();
        }
        return $this->options['handler'];
    }

    /**
     * @param array $config
     * @return OAuth2Middleware
     * @throws OauthException
     */
    private function createOAuthMiddleware(array $config): OAuth2Middleware
    {
        if (empty($config['token_uri'])) {
            throw new OauthException('token_uri must be configured for oauth');
        }
        $client = new Client([
            'base_uri' => $config['token_uri']
        ]);

        switch ($config['grant_type']) {
            case 'client_credentials':
                return new OAuth2Middleware(new ClientCredentials($client, $config));
            case 'password_credentials':
                return new OAuth2Middleware(new PasswordCredentials($client, $config));
        }
        throw new OauthException('oAuth grant type not configured correctly');
    }

}