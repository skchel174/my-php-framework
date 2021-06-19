<?php

namespace Framework\Http\Middlewares;

use Framework\Http\Middlewares\Interfaces\MiddlewareDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareDispatcher implements MiddlewareDispatcherInterface
{
    protected \SplQueue $middlewareQueue;

    public function __construct()
    {
        $this->middlewareQueue = new \SplQueue();
    }

    public function add($middleware): void
    {
        $this->middlewareQueue->enqueue($middleware);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $next = new Next($this->middlewareQueue, $handler);
        return $next->handle($request);
    }
}
