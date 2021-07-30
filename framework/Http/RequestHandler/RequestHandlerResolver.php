<?php

namespace Framework\Http\RequestHandler;

use Framework\Container\Interfaces\ContainerInterface;
use Framework\Http\RequestHandler\Exceptions\InvalidRequestHandlerActionException;
use Framework\Http\RequestHandler\Exceptions\InvalidRequestHandlerTypeException;
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
        $handler = $this->createHandler($handler);

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
            [$controller, $action] = $handler;
            $this->actionExistGuard($controller, $action);
            $controller = $this->createHandler($controller);

            return function (ServerRequestInterface $request) use ($controller, $action) {
                return $controller->$action($request);
            };
        }

        throw new InvalidRequestHandlerTypeException($handler);
    }

    protected function createHandler(mixed $handler)
    {
        if (is_string($handler) && class_exists($handler)) {
            $handler = $this->container->get($handler);
        }
        return $handler;
    }

    protected function actionExistGuard(string|object $controller, string $action): void
    {
        if (!method_exists($controller, $action)) {
            throw new InvalidRequestHandlerActionException($controller,  $action);
        }
    }
}
