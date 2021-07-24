<?php

namespace Tests\framework\ErrorHandler;

use Framework\ErrorHandler\HandlerDecorator;
use Framework\ErrorHandler\Interfaces\HandlerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HandlerDecoratorTest extends TestCase
{
    public function testWrapHandler(): void
    {
        $demoHandler = $this->getMockBuilder(HandlerInterface::class)->getMock();
        $dummyHandler = new DummyHandler();

        $this->assertNull($dummyHandler->getHandler());

        $dummyHandler->wrapHandler($demoHandler);

        $this->assertNotNull($dummyHandler->getHandler());
        $this->assertEquals($dummyHandler->getHandler(), $demoHandler);
    }

    public function testLog(): void
    {
        $demoHandler = $this->getMockBuilder(HandlerInterface::class)->getMock();
        $demoHandler->expects($this->once())
            ->method('log')
            ->withConsecutive([$exception = new \Exception()]);

        $dummyHandler = new DummyHandler();
        $dummyHandler->wrapHandler($demoHandler);

        $dummyHandler->log($exception);
    }

    public function testRender(): void
    {
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();

        $demoHandler = $this->getMockBuilder(HandlerInterface::class)->getMock();
        $demoHandler->expects($this->once())
            ->method('render')
            ->withConsecutive([$exception = new \Exception()])
            ->willReturn($response);

        $dummyHandler = new DummyHandler();
        $dummyHandler->wrapHandler($demoHandler);

        $result = $dummyHandler->render($exception, $request);

        $this->assertEquals($result, $response);
    }

    public function testHandle(): void
    {
        $exception = new \Exception();
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();

        $demoHandler = $this->getMockBuilder(HandlerInterface::class)->getMock();

        $demoHandler->expects($this->once())
            ->method('render')
            ->withConsecutive([$exception])
            ->willReturn($response);

        $demoHandler->expects($this->once())
            ->method('log')
            ->withConsecutive([$exception]);

        $dummyHandler = new DummyHandler();
        $dummyHandler->wrapHandler($demoHandler);

        $result = $dummyHandler->handle($exception, $request);

        $this->assertEquals($result, $response);
    }
}

class DummyHandler extends HandlerDecorator {}
