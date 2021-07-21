<?php

namespace Framework\ErrorHandler;

use Framework\Container\Interfaces\ContainerInterface;
use Framework\ErrorHandler\Exceptions\InvalidErrorHandlerTypeException;
use Framework\ErrorHandler\Interfaces\HandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LazyHandlerWrapper implements HandlerInterface
{
    private ContainerInterface $container;
    private mixed $handler;

    public function __construct(ContainerInterface $container, mixed $handler)
    {
        $this->container = $container;
        $this->handler = $handler;
    }

    public function handle(\Exception $e, ServerRequestInterface $request): ResponseInterface
    {
        $handler = $this->handler;

        if (is_string($handler)) {
            $handler = $this->container->get($this->handler);
        }

        if (is_callable($handler)) {
            return $handler($e, $request);
        }

        return $handler->handle($e, $request);
    }
}