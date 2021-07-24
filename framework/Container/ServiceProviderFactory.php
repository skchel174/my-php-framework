<?php

namespace Framework\Container;

use Psr\Container\ContainerInterface;

class ServiceProviderFactory
{
    const CONFIG_DIR = BASE_DIR . '/setup/config';
    const SERVICES_FILE = BASE_DIR . '/setup/services.php';

    public function __invoke(ContainerInterface $container): ServiceProvider
    {
        $provider = $container->get(ServiceProvider::class);
        $configLoader = new ConfigLoader(static::CONFIG_DIR);

        $provider->config('config', $configLoader->load());

        require static::SERVICES_FILE;

        return $provider;
    }
}
