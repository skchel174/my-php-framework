<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Interfaces\RouteInterface;
use Framework\Http\Router\Interfaces\RoutesCollectionInterface;

class RoutesCollection implements RoutesCollectionInterface
{
    protected array $routes = [];

    const HTTP_METHODS = [
        'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS', 'TRACE', 'CONNECT',
    ];

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function get(string $path, string|array $handler): RouteInterface
    {
        return $this->addRoute($path, $handler, ['GET']);
    }

    public function post(string $path, string|array $handler): RouteInterface
    {
        return $this->addRoute($path, $handler, ['POST']);
    }

    public function put(string $path, string|array $handler): RouteInterface
    {
        return $this->addRoute($path, $handler, ['PUT']);
    }

    public function patch(string $path, string|array $handler): RouteInterface
    {
        return $this->addRoute($path, $handler, ['PATCH']);
    }

    public function delete(string $path, string|array $handler): RouteInterface
    {
        return $this->addRoute($path, $handler, ['DELETE']);
    }

    public function head(string $path, string|array $handler): RouteInterface
    {
        return $this->addRoute($path, $handler, ['HEAD']);
    }

    public function options(string $path, string|array $handler): RouteInterface
    {
        return $this->addRoute($path, $handler, ['OPTIONS']);
    }

    public function trace(string $path, string|array $handler): RouteInterface
    {
        return $this->addRoute($path, $handler, ['TRACE']);
    }

    public function connect(string $path, string|array $handler): RouteInterface
    {
        return $this->addRoute($path, $handler, ['CONNECT']);
    }

    public function any(string $path, string|array $handler): RouteInterface
    {
        return $this->addRoute($path, $handler, self::HTTP_METHODS);
    }

    public function addRoute(string $path, string|array $handler, array $methods = []): RouteInterface
    {
        $this->routes[] = $route = new Route($path, $handler, $methods);
        return $route;
    }
}
