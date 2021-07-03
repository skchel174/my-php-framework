<?php

namespace Framework\Http\Middlewares\RequestHandler;

use Framework\Container\Interfaces\ContainerInterface;
use Framework\Http\Middlewares\RequestHandler\Exceptions\InvalidRequestHandlerTypeException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandlerResolver
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve(mixed $handler): callable
    {
        if (is_string($handler) && class_exists($handler)) {
            $handler = $this->container->get($handler);
        }

        if (is_object($handler)) {
            if ($handler instanceof RequestHandlerInterface) {
                return function (ServerRequestInterface $request) use ($handler) {
                    return $handler->handle($request);
                };
            }

            if (is_callable($handler)) {
                return function (ServerRequestInterface $request) use ($handler) {
                    return $handler($request);
                };
            }
        }

        if (is_array($handler) && count($handler) === 2) {
            return function (ServerRequestInterface $request) use ($handler) {
                [$controller, $action] = $handler;
                $controller = $this->container->get($controller);
                return $controller->$action($request);
            };
        }

        throw new InvalidRequestHandlerTypeException($handler);
    }
}
