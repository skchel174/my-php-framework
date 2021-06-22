<?php

namespace Tests\framework\Http\Middleware\MiddlewareDispatcher;

use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareWrapperInterface;
use Framework\Http\Middlewares\MiddlewareDispatcher\MiddlewareDispatcher;
use Framework\Http\Middlewares\MiddlewareDispatcher\MiddlewareResolver;
use Framework\Http\Middlewares\MiddlewareDispatcher\Next;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareDispatcherTest extends TestCase
{
    protected MiddlewareDispatcher $dispatcher;
    protected MockObject $resolver;

    public function setUp(): void
    {
        $this->resolver = $this
            ->getMockBuilder(MiddlewareResolver::class)
            ->getMock();
        $this->dispatcher = new MiddlewareDispatcher($this->resolver);
    }

    public function testAdd(): void
    {
        $this->resolver
            ->expects($this->exactly(1))
            ->method('resolve');

        $result = $this->dispatcher->add(function () {});

        $this->assertInstanceOf(MiddlewareWrapperInterface::class, $result);
    }

    public function testProcess(): void
    {
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $handler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();

        $result = $this->dispatcher->process($request, $handler);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}
