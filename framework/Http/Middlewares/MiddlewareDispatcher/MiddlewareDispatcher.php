<?php

namespace Framework\Http\Middlewares\MiddlewareDispatcher;

use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareWrapperInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;

class MiddlewareDispatcher implements MiddlewareInterface, MiddlewareDispatcherInterface
{
    protected \SplQueue $middlewareQueue;
    protected MiddlewareResolver $middlewareResolver;

    public function __construct(MiddlewareResolver $middlewareResolver)
    {
        $this->middlewareQueue = new \SplQueue();
        $this->middlewareResolver = $middlewareResolver;
    }

    public function add(mixed $middleware): MiddlewareWrapperInterface
    {
        $resolved = new MiddlewareWrapper($this->middlewareResolver->resolve($middleware));
        $this->middlewareQueue->enqueue($resolved);
        return $resolved;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $next = new Next($this->middlewareQueue, $handler);
        return $next($request);
    }
}
