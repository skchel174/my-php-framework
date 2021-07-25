<?php

namespace Framework\Container\Service;

use Framework\Container\Exceptions\ServiceConstructException;
use Framework\Container\Interfaces\ServiceInterface;
use Psr\Container\ContainerInterface as PsrContainerInterface;
use Framework\Container\Interfaces\ContainerInterface;

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

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function __invoke(ContainerInterface $container): object
    {
        if (is_object($this->service)) {
            return $this->service;
        }

        return $this->constructService($container, $this->service);
    }

    protected function constructService(ContainerInterface $container, string $service): object
    {
        $reflection = new \ReflectionClass($service);

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

            if ($type === ContainerInterface::class || $type === PsrContainerInterface::class) {
                $arguments[] = $container;
            } else if (array_key_exists($name, $this->arguments)) {
                $argument = $this->arguments[$name];
                if (is_string($argument) && $container->has($argument)) {
                    $argument = $container->get($argument);
                }
                $arguments[] = $argument;
            } else if ($container->has($type)) {
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
