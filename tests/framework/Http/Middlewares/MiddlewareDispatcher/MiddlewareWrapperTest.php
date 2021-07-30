<?php

namespace Tests\framework\Http\Middlewares\MiddlewareDispatcher;

use Framework\Http\Client\Request\ServerRequest;
use Framework\Http\MiddlewareDispatcher\MiddlewareWrapper;
use Framework\Http\Router\Route;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareWrapperTest extends TestCase
{
    public function testProcess(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);

        $middleware = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['__invoke'])
            ->getMock();
        $middleware
            ->expects($this->once())
            ->method('__invoke')
            ->withConsecutive([
                $this->equalTo($request),
                $this->equalTo($handler),
            ])
            ->willReturn($this->createMock(ResponseInterface::class));

        $wrapper = new MiddlewareWrapper($middleware);

        $result = $wrapper->process($request, $handler);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testIsAdmitByRoutePath(): void
    {
        $route = new Route($path = '/test', 'handler', ['GET']);
        $request = (new ServerRequest)->withAttribute(Route::class, $route);

        $wrapper = new MiddlewareWrapper(function() {});

        $wrapper->route($path);
        $result = $wrapper->isAdmitted($request);

        $this->assertTrue($result);
    }

    public function testIsAdmitByRouteName(): void
    {
        $route = new Route('/test', 'handler', ['GET']);
        $route->name($name = 'test');
        $request = (new ServerRequest)->withAttribute(Route::class, $route);

        $wrapper = new MiddlewareWrapper(function() {});
        $wrapper->route($name);

        $result = $wrapper->isAdmitted($request);

        $this->assertTrue($result);
    }

    /**
     * @dataProvider routesProvider
     */
    public function testIsAdmitByAnyRoutes(Route $route): void
    {
        $wrapper = new MiddlewareWrapper(function() {});
        $request = (new ServerRequest)->withAttribute(Route::class, $route);

        $wrapper->route(['/', '/test', 'test']);
        $result = $wrapper->isAdmitted($request);
        $this->assertTrue($result);
    }

    public function testIsNotAdmitted(): void
    {
        $route = new Route('/test', 'handler', ['GET']);
        $request = (new ServerRequest)->withAttribute(Route::class, $route);

        $wrapper = new MiddlewareWrapper(function() {});

        $wrapper->route('/');
        $result = $wrapper->isAdmitted($request);

        $this->assertFalse($result);
    }

    public function routesProvider(): array
    {
        return [
            '/' => [new Route('/', 'handler', ['GET'])],
            '/test' => [new Route('/test', 'handler', ['GET'])],
            'test' => [(new Route('/test', 'handler', ['GET']))->name('test')],
        ];
    }
}
