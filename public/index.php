<?php

use App\Http\Controllers\IndexController;
use App\Http\Middlewares\AppPerformanceMiddleware;
use App\Http\Middlewares\RouteDispatchMiddleware;
use Framework\Http\Client\Request\ServerRequestFactory;
use Framework\Http\Middlewares\MiddlewareDispatcher;
use Framework\Http\Controller\ControllerHandler;
use Framework\Http\Router\RouteDispatcher;
use Framework\Http\Router\RoutesCollection;

define('BASE_DIR', dirname(__DIR__));
define('START_TIME', microtime(true));
define('START_MEMORY', memory_get_usage());

require_once BASE_DIR . '/vendor/autoload.php';

$routes = new RoutesCollection();
$routes->get('/', [IndexController::class, 'index'])->name('home');
$routes->post('/{page}', [IndexController::class, 'page'])->params(['page' => '[a-z]+']);

$router = new RouteDispatcher($routes);
$request = (new ServerRequestFactory)->createFromSapi();

$middlewareDispatcher = new MiddlewareDispatcher();
$middlewareDispatcher->add(new AppPerformanceMiddleware());
$middlewareDispatcher->add(new RouteDispatchMiddleware($router));
$response = $middlewareDispatcher->process($request, new ControllerHandler());

header(sprintf('HTTP/%s %d %s',
    $response->getProtocolVersion(),
    $response->getStatusCode(),
    $response->getReasonPhrase()
));

foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}

echo $response->getBody();

