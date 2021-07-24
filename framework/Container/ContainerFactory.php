<?php

namespace Framework\Container;

use Psr\Container\ContainerInterface;

class ContainerFactory
{
    public function __invoke(): ContainerInterface
    {
        $container = Container::getInstance();
        (new ServiceProviderFactory)($container);
        return $container;
    }
}
