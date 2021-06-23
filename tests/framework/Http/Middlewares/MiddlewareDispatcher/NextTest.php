<?php

namespace Tests\framework\Http\Middlewares\MiddlewareDispatcher;

use Framework\Http\Client\Request\ServerRequest;
use Framework\Http\Middlewares\MiddlewareDispatcher\MiddlewareWrapper;
use Framework\Http\Middlewares\MiddlewareDispatcher\Next;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Tests\framework\Http\Middlewares\DummyMiddlewares\DummyRequestHandler;
use Tests\framework\Http\Middlewares\DummyMiddlewares\FirstDummyMiddleware;

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
