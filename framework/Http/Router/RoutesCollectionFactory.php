<?php

namespace Framework\Http\Router;

use Psr\Container\ContainerInterface;

class RoutesCollectionFactory
{
    const ROUTES_FILE = BASE_DIR . '/setup/routes.php';

    public function __invoke(ContainerInterface $container): RoutesCollection
    {
        $routes = $container->get(RoutesCollection::class);
        $this->routes($routes);
        return $routes;
    }

    protected function routes(RoutesCollection $routes): void
    {
        require static::ROUTES_FILE;
    }
}
