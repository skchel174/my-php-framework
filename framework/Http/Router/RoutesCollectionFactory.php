<?php

namespace Framework\Http\Router;

class RoutesCollectionFactory
{
    const ROUTES_FILE = BASE_DIR . '/setup/routes.php';

    public function __invoke(): RoutesCollection
    {
        $routes = new RoutesCollection();
        require static::ROUTES_FILE;
        return $routes;
    }
}
