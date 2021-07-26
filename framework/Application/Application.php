<?php

namespace Framework\Application;

use Framework\Container\ContainerFactory;
use Framework\ErrorHandler\Debugger;
use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\ResponseEmitter\Interfaces\ResponseEmitterInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Whoops\Run;

class Application
{
    protected MiddlewareDispatcherInterface $middlewares;
    protected ResponseEmitterInterface $responseEmitter;
    protected RequestHandlerInterface $requestHandler;

    public function __construct(
        MiddlewareDispatcherInterface $middlewares,
        ResponseEmitterInterface $responseEmitter,
        RequestHandlerInterface $requestHandler,
    )
    {
        $this->middlewares = $middlewares;
        $this->responseEmitter = $responseEmitter;
        $this->requestHandler = $requestHandler;
    }

    public function handleRequest(ServerRequestInterface $request): void
    {
        $response = $this->middlewares->process($request, $this->requestHandler);
        $this->responseEmitter->emit($response);
    }

    public static function run(ServerRequestInterface $request): void
    {
        try {
            $container = (new ContainerFactory)->create();
            $app = $container->get(Application::class);
            $app->handleRequest($request);
        } catch (\Exception $e) {
            $debugger = new Debugger(new Run());
            $debugger->handle($e, $request);
        }
    }
}
