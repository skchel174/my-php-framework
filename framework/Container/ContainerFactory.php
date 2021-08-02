<?php

namespace Framework\Container;

use Psr\Container\ContainerInterface;

class ContainerFactory
{
    const CONFIG_DIR = BASE_DIR . '/setup/config';
    const SERVICES_FILE = BASE_DIR . '/setup/services.php';

    public function create(): ContainerInterface
    {
        $container = Container::getInstance();

        $provider = $container->get(ServiceProvider::class);
        $this->services($container, $provider);

        return $container;
    }

    protected function services(ContainerInterface $container, ServiceProvider $provider): void
    {
        $configLoader = new ConfigLoader(static::CONFIG_DIR);
        $provider->config('config', $configLoader->load());

        require static::SERVICES_FILE;
    }
}
