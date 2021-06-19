<?php

use App\Controllers\IndexController;
use Framework\Http\Client\Request\ServerRequestFactory;
use Framework\Http\Router\RouteDispatcher;
use Framework\Http\Router\RoutesCollection;
use Psr\Http\Message\ResponseInterface;

define('BASE_DIR', dirname(__DIR__));

require_once BASE_DIR . '/vendor/autoload.php';

$routes = new RoutesCollection();
$routes->get('/', [IndexController::class, 'index'])->name('home');
$routes->post('/{page}', [IndexController::class, 'page'])->params(['page' => '[a-z]+']);

$router = new RouteDispatcher($routes);

$request = (new ServerRequestFactory)->createFromSapi();
$route = $router->dispatch($request);

foreach ($route->getAttributes() as $name => $value) {
    $request = $request->withAttribute($name, $value);
}

[$handler, $method] = $route->getHandler();
/** @var ResponseInterface $response */
$response = (new $handler)->$method($request);

echo $response->getBody();

