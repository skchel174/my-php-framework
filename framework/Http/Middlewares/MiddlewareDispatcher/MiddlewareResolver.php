<?php

namespace Framework\Http\Middlewares\MiddlewareDispatcher;

use Framework\Http\Middlewares\MiddlewareDispatcher\Exceptions\UnknownMiddlewareClassException;
use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareResolverInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Middlewares\MiddlewareDispatcher\Exceptions\InvalidMiddlewareTypeException;

class MiddlewareResolver implements MiddlewareResolverInterface
{
    public function resolve(mixed $middleware): callable
    {
        if (is_array($middleware)) {
            $middleware = $this->resolveArray($middleware);
            return function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($middleware) {
                $middleware->process($request, $handler);
            };
        }

        if ((is_string($middleware) && class_exists($middleware)) || is_object($middleware)) {
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
        throw new InvalidMiddlewareTypeException($middleware);
    }

    protected function resolveArray(array $middlewares): MiddlewareInterface
    {
        $dispatcher = new MiddlewareDispatcher($this);
        foreach ($middlewares as $middleware) {
            $dispatcher->add($middleware);
        }
        return $dispatcher;
    }
}
