<?php

namespace Tests\framework\Http\Middlewares;

use Framework\Http\Client\Request\ServerRequest;
use Framework\Http\Middlewares\MiddlewareDispatcher\MiddlewareDispatcher;
use Framework\Http\Middlewares\MiddlewareDispatcher\MiddlewareResolver;
use Framework\Http\Router\Route;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Tests\framework\Http\Middlewares\DummyMiddlewares\DummyRequestHandler;
use Tests\framework\Http\Middlewares\DummyMiddlewares\FirstDummyMiddleware;
use Tests\framework\Http\Middlewares\DummyMiddlewares\FourthDummyMiddleware;
use Tests\framework\Http\Middlewares\DummyMiddlewares\SecondDummyMiddleware;
use Tests\framework\Http\Middlewares\DummyMiddlewares\ThirdDummyMiddleware;

class MiddlewareFunctionalTest extends TestCase
{
    public function testProcess(): void
    {
        $request = (new ServerRequest)
            ->withAttribute(Route::class, new Route('/test', 'handler', ['GET']));
        $dispatcher = new MiddlewareDispatcher(new MiddlewareResolver());

        $dispatcher->add(new FirstDummyMiddleware());
        $dispatcher->add(new SecondDummyMiddleware())->route('/');
        $dispatcher->add([
            new ThirdDummyMiddleware(),
            new FourthDummyMiddleware(),
        ])->route('/test');

        $response = $dispatcher->process($request, new DummyRequestHandler());

        $this->assertInstanceOf(ResponseInterface::class, $response);

        $this->assertNotEmpty($body = json_decode($response->getBody(), true));

        $this->assertArrayHasKey(FirstDummyMiddleware::class, $body);
        $this->assertArrayNotHasKey(SecondDummyMiddleware::class, $body);
        $this->assertArrayHasKey(ThirdDummyMiddleware::class, $body);
        $this->assertArrayHasKey(FourthDummyMiddleware::class, $body);

        $this->assertArrayHasKey(FirstDummyMiddleware::class, $response->getHeaders());
        $this->assertArrayNotHasKey(SecondDummyMiddleware::class, $response->getHeaders());
        $this->assertArrayHasKey(ThirdDummyMiddleware::class, $response->getHeaders());
        $this->assertArrayHasKey(FourthDummyMiddleware::class, $response->getHeaders());
    }
}
