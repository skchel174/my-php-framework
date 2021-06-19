<?php

namespace Tests\framework\Http\Router;

use Framework\Http\Client\Request\ServerRequest;
use Framework\Http\Client\Uri\UriFactory;
use Framework\Http\Router\Exceptions\ParameterNotAssignException;
use Framework\Http\Router\Exceptions\RouteNotExistsException;
use Framework\Http\Router\Exceptions\RouteNotFoundException;
use Framework\Http\Router\Route;
use Framework\Http\Router\RouteDispatcher;
use Framework\Http\Router\RoutesCollection;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class RouteDispatcherTest extends TestCase
{
    public function testDispatchSimpleRoute(): void
    {
        $routes = new RoutesCollection();
        $routes->get($getPath = '/get', $getHandler = 'get_handler');
        $routes->post($postPath = '/post', $postHandler = 'post_handler');

        $router = new RouteDispatcher($routes);
        $getRoute = $router->dispatch($this->createRequest('GET', 'http://localhost' . $getPath));
        $postRoute = $router->dispatch($this->createRequest('POST', 'http://localhost' . $postPath));

        $this->assertInstanceOf(Route::class, $getRoute);
        $this->assertInstanceOf(Route::class, $postRoute);

        $this->assertEquals($getPath, $getRoute->getPath());
        $this->assertEquals($postPath, $postRoute->getPath());

        $this->assertEquals($getHandler, $getRoute->getHandler());
        $this->assertEquals($postHandler, $postRoute->getHandler());
    }

    public function testDispatchRouteWithParams(): void
    {
        $getId = 43;
        $getParam = 'resource_get';

        $postId = 78;
        $postParam = 'resource_post';

        $routes = new RoutesCollection();
        $routes->get('/get/{id}/{resource}', $getHandler = 'get_handler')->params(['id' => '\d+', 'resource' => '[a-z_]+']);
        $routes->post('/post/{id}/{resource}', $postHandler = 'post_handler')->params(['id' => '\d+', 'resource' => '[a-z_]+']);

        $router = new RouteDispatcher($routes);
        $getRoute = $router->dispatch($this->createRequest('GET', 'http://localhost/get/' . $getId . '/' . $getParam));
        $postRoute = $router->dispatch($this->createRequest('POST', 'http://localhost/post/' . $postId . '/' . $postParam));

        $this->assertInstanceOf(Route::class, $getRoute);
        $this->assertInstanceOf(Route::class, $postRoute);

        $this->assertEquals($getHandler, $getRoute->getHandler());
        $this->assertEquals($postHandler, $postRoute->getHandler());

        $this->assertEquals(['id' => $getId, 'resource' => $getParam], $getRoute->getAttributes());
        $this->assertEquals(['id' => $postId,'resource' => $postParam], $postRoute->getAttributes());
    }

    public function testDispatchInvalidRoute(): void
    {
        $this->expectException(RouteNotExistsException::class);

        $routes = new RoutesCollection();
        $routes->get('/get', 'get_handler');

        $router = new RouteDispatcher($routes);
        $route = $router->dispatch($this->createRequest('GET', 'http://localhost/get/resource'));
    }

    public function testDispatchInvalidRouteWithParams(): void
    {
        $this->expectException(RouteNotExistsException::class);

        $routes = new RoutesCollection();
        $routes->get('/get/{id}', 'get_handler')->params(['id' => '\d+']);

        $router = new RouteDispatcher($routes);
        $route = $router->dispatch($this->createRequest('GET', 'http://localhost/get/43a'));
    }

    public function testDispatchInvalidRouteWithMethod(): void
    {
        $this->expectException(RouteNotExistsException::class);

        $routes = new RoutesCollection();
        $routes->get('/get', 'get_handler');

        $router = new RouteDispatcher($routes);
        $route = $router->dispatch($this->createRequest('POST', 'http://localhost/get'));
    }

    public function testCreateSimpleRoute(): void
    {
        $routes = new RoutesCollection();
        $routes->get($getPath = '/get', 'get_handler')->name('get_route');
        $routes->get($postPath = '/post', 'post_handler')->name('post_route');

        $router = new RouteDispatcher($routes);
        $getRoute = $router->route('get_route');
        $postRoute = $router->route('post_route');

        $this->assertEquals($getRoute, $getPath);
        $this->assertEquals($postRoute, $postPath);
    }

    public function testCreateRouteWithParamsAndQueryString(): void
    {
        $getId = 43;
        $getParam = 'resource_get';

        $postId = 78;
        $postParam = 'resource_post';

        $routes = new RoutesCollection();
        $routes->get('/get/{id}/{resource}', 'get_handler')
            ->params(['id' => '\d+', 'resource' => '[a-z_]+'])
            ->name('get_route');

        $routes->post('/post/{id}/{resource}', 'post_handler')
            ->params(['id' => '\d+', 'resource' => '[a-z_]+'])
            ->name('post_route');

        $router = new RouteDispatcher($routes);
        $getRoute = $router->route('get_route', ['id' => $getId, 'resource' => $getParam, 'query_param' => 'query_value']);
        $postRoute = $router->route('post_route', ['id' => $postId, 'resource' => $postParam]);

        $this->assertEquals($getRoute, '/get/' . $getId . '/' . $getParam . '?query_param=query_value');
        $this->assertEquals($postRoute, '/post/' . $postId . '/' . $postParam );
    }

    public function testCreateInvalidRoute(): void
    {
        $this->expectException(RouteNotFoundException::class);

        $routes = new RoutesCollection();
        $routes->get($getPath = '/get', 'get_handler')->name('get_route');

        $router = new RouteDispatcher($routes);
        $getRoute = $router->route('post_route');

        $this->assertEquals($getRoute, $getPath);
    }

    public function testCreateRouteWithInvalidParams(): void
    {
        $this->expectException(ParameterNotAssignException::class);

        $routes = new RoutesCollection();
        $routes->get('/get/{id}', 'get_handler')
            ->params(['id' => '\d+'])
            ->name('get_route');

        $router = new RouteDispatcher($routes);
        $getRoute = $router->route('get_route', ['name' => '43']);

        $this->assertEquals($getRoute, '/get/43');
    }

    protected function createRequest(string $method, string $path): ServerRequestInterface
    {
        return (new ServerRequest)
            ->withMethod($method)
            ->withUri((new UriFactory)->createUri($path));
    }
}
