<?php

namespace Framework\Http\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Next implements RequestHandlerInterface
{
    protected \SplQueue $middlewareQueue;
    protected RequestHandlerInterface $defaultHandler;

    public function __construct(\SplQueue $middlewareQueue, RequestHandlerInterface $defaultHandler)
    {
        $this->middlewareQueue = $middlewareQueue;
        $this->defaultHandler = $defaultHandler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->middlewareQueue->isEmpty()) {
            return $this->defaultHandler->handle($request);
        }

        /** @var MiddlewareInterface $middleware */
        $middleware = $this->middlewareQueue->dequeue();

        $next = new Next($this->middlewareQueue, $this->defaultHandler);
        return $middleware->process($request, $next);
    }
}
