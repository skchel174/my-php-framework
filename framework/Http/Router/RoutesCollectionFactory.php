<?php

namespace Framework\Http\Router;

use Psr\Container\ContainerInterface;

class RoutesCollectionFactory
{
    const ROUTES_FILE = BASE_DIR . '/setup/routes.php';

    public function __invoke(ContainerInterface $container): RoutesCollection
    {
        $routes = $container->get(RoutesCollection::class);
        require static::ROUTES_FILE;
        return $routes;
    }
}
