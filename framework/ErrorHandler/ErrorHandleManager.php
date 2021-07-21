<?php

namespace Framework\ErrorHandler;

use Framework\Container\Container;
use Framework\ErrorHandler\Interfaces\ErrorsManagerInterface;
use Framework\ErrorHandler\Interfaces\HandlerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ErrorHandleManager implements ErrorsManagerInterface
{
    private Container $container;
    private HandlersCollection $handlers;

    public function __construct(ContainerInterface $container, HandlersCollection $handlers)
    {
        $this->container = $container;
        $this->handlers= $handlers;
    }

    public function process(\Exception $e, ServerRequestInterface $request): ResponseInterface
    {
        $handler = $this->buildSequence($e::class);
        return $handler->handle($e, $request);
    }

    protected function buildSequence(string $exception): HandlerInterface
    {
        $handlerName = $this->handlers->get(\Exception::class);
        $handler = $this->container->get($handlerName);

        while ($this->handlers->has($exception)) {
            $handlerName = $this->handlers->get($exception);

            $handler = $this->container->get($handlerName)->wrapUp($handler);

            $reflection = new \ReflectionClass($exception);
            $exception = $reflection->getParentClass();
        }

        return $handler;
    }
}
