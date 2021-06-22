<?php

namespace Tests\framework\Http\Middleware\MiddlewareDispatcher;

use Framework\Http\Client\Request\ServerRequest;
use Framework\Http\Client\Response\Response;
use Framework\Http\Middlewares\MiddlewareDispatcher\MiddlewareWrapper;
use Framework\Http\Middlewares\MiddlewareDispatcher\Next;
use Framework\Http\Router\Route;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NextTest extends TestCase
{
    public function testCall(): void
    {
        $queue = new \SplQueue();

        $request = (new ServerRequest)
            ->withAttribute(Route::class, new Route('/test', 'handler', ['GET']));

        $queue->enqueue(new MiddlewareWrapper(new FirstDummyMiddleware()));
        $queue->enqueue((new MiddlewareWrapper(new SecondDummyMiddleware()))->route('/'));
        $queue->enqueue(new MiddlewareWrapper(new ThirdDummyMiddleware()));

        $next = new Next($queue, new DummyRequestHandler());
        $result = $next($request);

        $this->assertInstanceOf(ResponseInterface::class, $result);

        $this->assertNotEmpty($body = json_decode($result->getBody(), true));

        $this->assertArrayHasKey(FirstDummyMiddleware::class, $body);
        $this->assertArrayNotHasKey(SecondDummyMiddleware::class, $body);
        $this->assertArrayHasKey(ThirdDummyMiddleware::class, $body);
    }
}

class FirstDummyMiddleware
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute(self::class, true);
        return $handler->handle($request);
    }
}

class SecondDummyMiddleware
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute(self::class, true);
        return $handler->handle($request);
    }
}

class ThirdDummyMiddleware
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute(self::class, true);
        return $handler->handle($request);
    }
}

class DummyRequestHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return (new Response)->withBody(json_encode($request->getAttributes()));
    }
}
