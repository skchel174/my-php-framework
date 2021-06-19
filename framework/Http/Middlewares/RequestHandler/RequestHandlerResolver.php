<?php

namespace Framework\Http\Middlewares\RequestHandler;

use Framework\Http\Middlewares\RequestHandler\Exceptions\InvalidRequestHandlerTypeException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandlerResolver
{
    public function resolve(mixed $handler): callable
    {
        if (is_string($handler) && class_exists($handler)) {
            $handler = new $handler();
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

        if (is_array($handler)) {
            return function (ServerRequestInterface $request) use ($handler) {
                [$controller, $action] = $handler;
                $controller = new $controller();
                return $controller->$action($request);
            };
        }

        throw new InvalidRequestHandlerTypeException($handler);
    }
}
