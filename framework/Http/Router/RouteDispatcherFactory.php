<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Interfaces\RouteDispatcherInterface;
use Psr\Container\ContainerInterface;

class RouteDispatcherFactory
{
    const ROUTES_FILE = BASE_DIR . '/bootstrap/routes.php';

    public function __invoke(ContainerInterface $container): RouteDispatcherInterface
    {
        $routes = $container->get(RoutesCollection::class);
        $this->routes($routes);
        return new RouteDispatcher($routes);
    }

    protected function routes(RoutesCollection $routes): void
    {
        require static::ROUTES_FILE;
    }
}
