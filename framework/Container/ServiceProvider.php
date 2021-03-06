<?php

namespace Framework\Container;

use Framework\Container\Interfaces\ContainerInterface;
use Framework\Container\Interfaces\ServiceInterface;
use Framework\Container\Service\CallableService;
use Framework\Container\Service\FactoryService;
use Framework\Container\Service\Service;

class ServiceProvider
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function config(string $id, mixed $value): void
    {
        $this->container->set($id, $value);
    }

    public function service(string|object $service): ServiceInterface
    {
        $serviceId = is_object($service) ? get_class($service) : $service;
        $service = new Service($service);
        $this->container->set($serviceId, $service);
        return $service;
    }

    public function alias(string $serviceId, string|object $service): ServiceInterface
    {
        $service = new Service($service);
        $this->container->set($serviceId, $service);
        return $service;
    }

    public function factory(string $serviceId, string|object $factory): ServiceInterface
    {
        $service = new FactoryService($factory);
        $this->container->set($serviceId, $service);
        return $service;
    }

    public function callable(string $serviceId, callable $callable): ?ServiceInterface
    {
        $service = new CallableService($callable);
        $this->container->set($serviceId, $service);
        return $service;
    }
}
