<?php

use App\Controllers\IndexController;
use Framework\Http\Client\Request\ServerRequestFactory;
use Framework\Http\Middlewares\MiddlewareDispatcher;
use Framework\Http\Router\Interfaces\RouteDispatcherInterface;
use Framework\Http\Router\Route;
use Framework\Http\Router\RouteDispatcher;
use Framework\Http\Router\RoutesCollection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

define('BASE_DIR', dirname(__DIR__));
define('START_TIME', microtime(true));
define('START_MEMORY', memory_get_usage());

require_once BASE_DIR . '/vendor/autoload.php';

$routes = new RoutesCollection();
$routes->get('/', [IndexController::class, 'index'])->name('home');
$routes->post('/{page}', [IndexController::class, 'page'])->params(['page' => '[a-z]+']);

$router = new RouteDispatcher($routes);
$request = (new ServerRequestFactory)->createFromSapi();

class AppPerformanceMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $time = microtime(true) - START_TIME;
        $memory = memory_get_usage() - START_MEMORY;

        return $response
            ->withHeader('X-Time-Used', [$time])
            ->withHeader('X-Memory-Used', [$memory]);
    }
}

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
        foreach ($route->getAttributes() as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }
        $request = $request->withAttribute(Route::REQUEST_HANDLER, $route->getHandler());
        return $handler->handle($request);
    }
}

class ControllerHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var ResponseInterface $response */
        [$handler, $method] = $request->getAttribute(Route::REQUEST_HANDLER);
        return (new $handler)->$method($request);
    }
}

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

