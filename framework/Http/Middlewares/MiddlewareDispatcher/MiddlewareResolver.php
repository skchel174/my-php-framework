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
        $this->middlewareTypeGuard($middleware);

        if (is_array($middleware)) {
            $middleware = $this->resolveArray($middleware);

            return function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($middleware) {
                $middleware->process($request, $handler);
            };
        }

        $reflection = new \ReflectionClass($middleware);

        if ($reflection->implementsInterface(MiddlewareInterface::class)) {

            return function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($middleware) {
                if (!is_object($middleware)) {
                    $middleware = new $middleware();
                }
                return $middleware->process($request, $handler);
            };
        }

        if ($reflection->hasMethod('__invoke')) {
            $method = $reflection->getMethod('__invoke');
            $parameters = $method->getParameters();

            if ($parameters[0]->getType() === ServerRequestInterface::class &&
                $parameters[1]->getType() === RequestHandlerInterface::class) {

                return function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($middleware) {
                    if (!is_object($middleware)) {
                        $middleware = new $middleware();
                    }
                    return $middleware($request, $handler);
                };
            }
        }

        throw new UnknownMiddlewareClassException($middleware);
    }

    protected function resolveArray(array $middlewares): MiddlewareInterface
    {
        $dispatcher = new MiddlewareDispatcher($this);
        foreach ($middlewares as $middleware) {
            $dispatcher->add($middleware);
        }
        return $dispatcher;
    }

    protected function middlewareTypeGuard(mixed $middleware): void
    {
        if (!(is_string($middleware) && class_exists($middleware)) && !is_object($middleware) && !is_array($middleware)) {
            throw new InvalidMiddlewareTypeException($middleware);
        }
    }
}
