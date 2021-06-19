<?php

namespace App\Http\Middlewares;

use Framework\Http\Controller\ControllerHandler;
use Framework\Http\Middlewares\MiddlewareWrapper;
use Framework\Http\Router\Interfaces\RouteDispatcherInterface;
use Framework\Http\Router\Route;
use Framework\Http\Router\RouteDispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteDispatchMiddleware implements MiddlewareInterface
{
    private RouteDispatcher $router;

    public function __construct(RouteDispatcherInterface $router)
    {
        $this->router = $router;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->router->dispatch($request);

        $attributes = array_merge($route->getAttributes(), [
            MiddlewareWrapper::ROUTE => $route->getName() ?? $route->getPath(),
            ControllerHandler::HANDLER => $route->getHandler(),
        ]);

        foreach ($attributes as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        return $handler->handle($request);
    }
}
