<?php

namespace Tests\framework\Http\Middleware\MiddlewareDispatcher;

use Framework\Http\Client\Request\ServerRequest;
use Framework\Http\Middlewares\MiddlewareDispatcher\MiddlewareWrapper;
use Framework\Http\Middlewares\MiddlewareDispatcher\Next;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Tests\framework\Http\Middleware\DummyMiddlewares\DummyRequestHandler;
use Tests\framework\Http\Middleware\DummyMiddlewares\FirstDummyMiddleware;

class NextTest extends TestCase
{
    public function testCall(): void
    {
        $queue = new \SplQueue();
        $queue->enqueue(new MiddlewareWrapper(new FirstDummyMiddleware()));

        $next = new Next($queue, new DummyRequestHandler());
        $response = $next(new ServerRequest());

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertNotEmpty($body = json_decode($response->getBody(), true));
        $this->assertArrayHasKey(FirstDummyMiddleware::class, $body);
        $this->assertArrayHasKey(FirstDummyMiddleware::class, $response->getHeaders());
    }
}
