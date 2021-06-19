<?php

namespace Framework\Http\Middlewares\MiddlewareDispatcher;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Middlewares\MiddlewareDispatcher\Exceptions\InvalidMiddlewareTypeException;
use Framework\Http\Middlewares\MiddlewareDispatcher\Exceptions\UnknownMiddlewareClassException;

class MiddlewareResolver
{
    public function resolve(mixed $middleware): callable
    {
        $this->middlewareTypeGuard($middleware);

        return function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($middleware) {
            if (!is_object($middleware)) {
                $middleware = new $middleware();
            }

            if ($middleware instanceof MiddlewareInterface || method_exists($middleware, 'process')) {
                return $middleware->process($request, $handler);
            }

            if (is_callable($middleware)) {
                return $middleware($request, $handler);
            }

            throw new UnknownMiddlewareClassException($middleware);
        };
    }

    public function middlewareTypeGuard(mixed $middleware): void
    {
        if (!(is_string($middleware) && class_exists($middleware)) && !is_object($middleware)) {
            throw new InvalidMiddlewareTypeException($middleware);
        }
    }
}
