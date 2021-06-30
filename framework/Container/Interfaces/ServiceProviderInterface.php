<?php

namespace Framework\Container\Interfaces;

interface ServiceProviderInterface
{
    public function config(string $id, mixed $value): void;
    public function service(string|object $service): ServiceInterface;
    public function alias(string $serviceId, string|object $alias): ServiceInterface;
    public function factory(string $serviceId, string|object $factory): ServiceInterface;
    public function callable(string $serviceId, callable $callable): ?ServiceInterface;
}
