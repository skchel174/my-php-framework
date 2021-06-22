<?php

namespace Tests\framework\Http\Middleware\MiddlewareDispatcher;

use Framework\Http\Client\Request\ServerRequest;
use Framework\Http\Client\Response\Response;
use Framework\Http\Middlewares\MiddlewareDispatcher\MiddlewareWrapper;
use Framework\Http\Middlewares\MiddlewareDispatcher\Next;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NextTest extends TestCase
{
    public function testCall(): void
    {
        $queue = new \SplQueue();
        $queue->enqueue(new MiddlewareWrapper(new DummyMiddleware()));

        $next = new Next($queue, new DummyRequestHandler());
        $result = $next(new ServerRequest());

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}

class DummyMiddleware
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request);
    }
}

class DummyRequestHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
         return new Response();
    }
}
