<?php

namespace Framework\Http\Middlewares\MiddlewareDispatcher;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Next
{
    protected \SplQueue $middlewareQueue;
    protected RequestHandlerInterface $defaultHandler;

    public function __construct(\SplQueue $middlewareQueue, RequestHandlerInterface $defaultHandler)
    {
        $this->middlewareQueue = $middlewareQueue;
        $this->defaultHandler = $defaultHandler;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->middlewareQueue->isEmpty()) {
            return $this->defaultHandler->handle($request);
        }

        /** @var MiddlewareWrapper $middleware */
        $middleware = $this->middlewareQueue->dequeue();

        if (!$middleware->isAdmitted($request)) {
            return $this($request);
        }

        $next = new static($this->middlewareQueue, $this->defaultHandler);
        return $middleware->process($request, new RequestHandlerWrapper($next));
    }
}
