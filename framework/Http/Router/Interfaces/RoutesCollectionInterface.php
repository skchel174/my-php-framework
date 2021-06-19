<?php

namespace Framework\Http\Router\Interfaces;

interface RoutesCollectionInterface
{
    public function getRoutes(): array;

    public function addRoute(string $path, string|array $handler, array $methods = []): RouteInterface;

    public function get(string $path, string|array $handler): RouteInterface;

    public function post(string $path, string|array $handler): RouteInterface;

    public function put(string $path, string|array $handler): RouteInterface;

    public function patch(string $path, string|array $handler): RouteInterface;

    public function delete(string $path, string|array $handler): RouteInterface;

    public function head(string $path, string|array $handler): RouteInterface;

    public function options(string $path, string|array $handler): RouteInterface;

    public function trace(string $path, string|array $handler): RouteInterface;

    public function connect(string $path, string|array $handler): RouteInterface;

    public function any(string $path, string|array $handler): RouteInterface;
}
