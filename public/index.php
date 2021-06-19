<?php

use Framework\Http\Client\Request\ServerRequestFactory;
use Framework\Http\Router\RouteDispatcher;
use Framework\Http\Router\RoutesCollection;
use Psr\Http\Message\ServerRequestInterface;

define('BASE_DIR', dirname(__DIR__));

require_once BASE_DIR . '/vendor/autoload.php';

class HomeController
{
    public function index(ServerRequestInterface $request)
    {
        echo '<form action="/home" method="POST">
                <input type="submit">
            </form>';
    }

    public function page(ServerRequestInterface $request)
    {
        echo __CLASS__ . ' / ' . __METHOD__ . '<br>';
        var_dump($request->getAttributes());
    }
}

$routes = new RoutesCollection();
$routes->get('/', [HomeController::class, 'index'])->name('home');
$routes->post('/{page}', [HomeController::class, 'page'])->params(['page' => '[a-z]+']);

$router = new RouteDispatcher($routes);

$request = (new ServerRequestFactory)->createFromSapi();
$route = $router->dispatch($request);

foreach ($route->getAttributes() as $name => $value) {
    $request = $request->withAttribute($name, $value);
}

[$handler, $method] = $route->getHandler();
(new $handler)->$method($request);


