<?php

namespace Framework\Container\Service;

use Framework\Container\Interfaces\ContainerInterface;

class FactoryService extends Service
{
    public function __invoke(ContainerInterface $container): object
    {
        $service = is_string($this->service) ? new $this->service() : $this->service;

        $reflection = new \ReflectionClass($service);
        $factoryMethod = $reflection->getMethod('__invoke');

        $arguments = $this->initParameters($container, $factoryMethod->getParameters());
        return $service(...$arguments);
    }
}
