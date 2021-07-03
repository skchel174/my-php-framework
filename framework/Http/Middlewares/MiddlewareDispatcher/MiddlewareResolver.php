<?php

namespace Framework\Http\Middlewares\MiddlewareDispatcher;

use Framework\Container\Interfaces\ContainerInterface;
use Framework\Http\Middlewares\MiddlewareDispatcher\Exceptions\UnknownMiddlewareClassException;
use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareResolverInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Middlewares\MiddlewareDispatcher\Exceptions\InvalidMiddlewareTypeException;
use ReflectionParameter;

class MiddlewareResolver implements MiddlewareResolverInterface
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve(mixed $middleware): callable
    {
        $this->middlewareTypeGuard($middleware);

        if (is_array($middleware)) {
            $middleware = $this->resolveArray($middleware);
            return function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($middleware) {
                return $middleware->process($request, $handler);
            };
        }

        $reflection = new \ReflectionClass($middleware);

        if ($reflection->implementsInterface(MiddlewareInterface::class)) {
            return function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($middleware) {
                if (!is_object($middleware)) {
                    $middleware = $this->container->get($middleware);
                }
                return $middleware->process($request, $handler);
            };
        }

        if ($reflection->getName() == \Closure::class) {
            $funcReflection = new \ReflectionFunction($middleware);
            $parameters = $funcReflection->getParameters();

            if ($this->parametersTypeGuard($parameters)) {
                return function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($middleware) {
                    if (!is_object($middleware)) {
                        $middleware = $this->container->get($middleware);
                    }
                    return $middleware($request, $handler);
                };
            }
        }

        if ($reflection->hasMethod('__invoke')) {
            $method = $reflection->getMethod('__invoke');
            $parameters = $method->getParameters();

            if ($this->parametersTypeGuard($parameters)) {
                return function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($middleware) {
                    if (!is_object($middleware)) {
                        $middleware = $this->container->get($middleware);
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

    /**
     * @param ReflectionParameter[] $parameters
     * @return bool
     */
    protected function parametersTypeGuard(array $parameters): bool
    {
        if (count($parameters) >= 2) {
            return $parameters[0]->getType() == ServerRequestInterface::class
                && $parameters[1]->getType() == RequestHandlerInterface::class;
        }
        return false;
    }
}
