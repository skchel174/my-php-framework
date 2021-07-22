<?php

namespace Framework\ErrorHandler;

use Framework\ErrorHandler\Exceptions\InvalidErrorHandlerTypeException;
use Framework\ErrorHandler\Interfaces\HandlerInterface;
use Psr\Container\ContainerInterface;

class HandlersCollection
{
    private array $handlers = [];
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function register(string $exception, mixed $handler): void
    {
        $this->handlerTypeGuard($handler);
        $this->handlers[$exception] = $handler;
    }

    public function get(string $exception): ?HandlerInterface
    {
        if (!$this->has($exception)) {
            return null;
        }

        $handler = $this->handlers[$exception];

        if (is_string($handler)) {
            $handler = $this->container->get($handler);
        }

        return $handler;
    }

    public function has(string $exception): bool
    {
        return array_key_exists($exception, $this->handlers);
    }

    protected function handlerTypeGuard(mixed $handler)
    {
        $reflection = new \ReflectionClass($handler);
        if (!$reflection->implementsInterface(HandlerInterface::class)) {
            throw new InvalidErrorHandlerTypeException($handler);
        }
    }
}