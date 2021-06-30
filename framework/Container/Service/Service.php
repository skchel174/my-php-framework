<?php

namespace Framework\Container\Service;

use Framework\Container\Exceptions\ServiceConstructException;
use Framework\Container\Interfaces\ContainerInterface;
use Framework\Container\Interfaces\ServiceInterface;

class Service implements ServiceInterface
{
    protected string|object $service;
    protected bool $shared = true;
    protected array $arguments = [];

    public function __construct(string|object $service)
    {
        $this->service = $service;
    }

    public function isShared(): bool
    {
        return $this->shared;
    }

    public function shared(bool $shared = true): ServiceInterface
    {
        $this->shared = $shared;
        return $this;
    }

    public function argument(string $name, mixed $argument): ServiceInterface
    {
        $this->arguments[$name] = $argument;
        return $this;
    }

    public function construct(ContainerInterface $container): object
    {
        if (is_object($this->service)) {
            return $this->service;
        }

        $reflection = new \ReflectionClass($this->service);

        if ($constructor = $reflection->getConstructor()) {
            $arguments = $this->initParameters($container, $constructor->getParameters());
        }

        return $reflection->newInstanceArgs($arguments ?? []);
    }

    protected function initParameters(ContainerInterface $container, array $parameters): array
    {
        $arguments = [];
        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            $type = $parameter->getType()->getName();

            if (array_key_exists($name, $this->arguments)) {
                $arguments[] = $container->get($this->arguments[$name]);
            } else if ($type === ContainerInterface::class) {
                $arguments[] = $container;
            } else if (class_exists($type)) {
                $arguments[] = $container->get($type);
            } else {
                if (!$parameter->isDefaultValueAvailable()) {
                    throw new ServiceConstructException($parameter->getName(), $this->service);
                }
                $arguments[] = $parameter->getDefaultValue();
            }
        }
        return $arguments;
    }
}
