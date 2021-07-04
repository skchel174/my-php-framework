<?php

namespace Framework\Application;

use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\ResponseEmitter\Interfaces\ResponseEmitterInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Application
{
    protected MiddlewareDispatcherInterface $middlewareDispatcher;
    protected ResponseEmitterInterface $responseEmitter;
    protected RequestHandlerInterface $requestHandler;

    public function __construct(
        MiddlewareDispatcherInterface $middlewareDispatcher,
        ResponseEmitterInterface $responseEmitter,
        RequestHandlerInterface $requestHandler,
    )
    {
        $this->middlewareDispatcher = $middlewareDispatcher;
        $this->responseEmitter = $responseEmitter;
        $this->requestHandler = $requestHandler;
    }

    public function run(ServerRequestInterface $request): void
    {
        $response = $this->middlewareDispatcher->process($request, $this->requestHandler);
        $this->responseEmitter->emit($response);
    }
}
