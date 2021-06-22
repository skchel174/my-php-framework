<?php

namespace Tests\framework\Http\Middleware;

use Framework\Http\Client\Request\ServerRequest;
use Framework\Http\Client\Response\Response;
use Framework\Http\Middlewares\MiddlewareDispatcher\MiddlewareDispatcher;
use Framework\Http\Middlewares\MiddlewareDispatcher\MiddlewareResolver;
use Framework\Http\Router\Route;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

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

class FirstDummyMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute(self::class, true);
        $response = $handler->handle($request);
        return $response->withHeader(self::class, true);
    }
}

class SecondDummyMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute(self::class, true);
        $response = $handler->handle($request);
        return $response->withHeader(self::class, true);;
    }
}

class ThirdDummyMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute(self::class, true);
        $response = $handler->handle($request);
        return $response->withHeader(self::class, true);;
    }
}

class FourthDummyMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute(self::class, true);
        $response = $handler->handle($request);
        return $response->withHeader(self::class, true);;
    }
}

class DummyRequestHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        return $response->withBody(json_encode($request->getAttributes()));
    }
}
