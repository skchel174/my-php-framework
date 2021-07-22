<?php

namespace Tests\framework\ErrorHandler;

use Framework\ErrorHandler\Exceptions\InvalidErrorHandlerTypeException;
use Framework\ErrorHandler\HandlersCollection;
use Framework\ErrorHandler\Interfaces\HandlerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class HandlersCollectionTest extends TestCase
{
    public ContainerInterface $container;

    public function setUp(): void
    {
        $this->container = $this->getMockBuilder(ContainerInterface::class)->getMock();
    }

    public function testEmpty(): void
    {
        $handlers = new HandlersCollection($this->container);
        $handler = $handlers->get(\Exception::class);

        $this->assertNull($handler);
    }

    public function testGetObjectFromObject(): void
    {
        $demoHandler = $this->getMockBuilder(HandlerInterface::class)->getMock();
        $handlers = new HandlersCollection($this->container);

        $handlers->register($id = \Exception::class, $demoHandler);
        $handler = $handlers->get($id);

        $this->assertNotNull($handler);
        $this->assertEquals($handler, $demoHandler);
    }

    public function testGetObjectFromString(): void
    {
        $demoHandler = $this->getMockBuilder(HandlerInterface::class)->getMock();
        $this->container->expects($this->once())
            ->method('get')
            ->withConsecutive([HandlerInterface::class])
            ->willReturn($demoHandler);

        $handlers = new HandlersCollection($this->container);
        $handlers->register($id = \Exception::class, HandlerInterface::class);
        $handler = $handlers->get($id);

        $this->assertNotNull($handler);
        $this->assertEquals($handler, $demoHandler);
    }

    public function testHandlerTypeGuard(): void
    {
        $this->expectException(InvalidErrorHandlerTypeException::class);

        $handlers = new HandlersCollection($this->container);
        $handler = $this->getMockBuilder(\stdClass::class)->getMock();
        $handlers->register(\Exception::class, $handler);
    }
}
