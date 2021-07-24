<?php

namespace Tests\framework\ErrorHandler;

use Framework\ErrorHandler\ErrorManager;
use Framework\ErrorHandler\HandlerDecorator;
use Framework\ErrorHandler\HandlersCollection;
use Framework\ErrorHandler\Interfaces\HandlerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ErrorManagerTest extends TestCase
{
    public function testProcess(): void
    {
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();

        $demoHandler = $this->getMockBuilder(HandlerInterface::class)->getMock();

        $exception = new \Exception();

        $demoHandler->expects($this->once())
            ->method('handle')
            ->withConsecutive([$exception, $request])
            ->willReturn($response);

        $handlers = $this->getMockBuilder(HandlersCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $handlers->expects($this->never())
            ->method('has');

        $handlers->expects($this->once())
            ->method('get')
            ->withConsecutive([\Exception::class])
            ->willReturn($demoHandler);

        $manager = new ErrorManager($handlers);
        $result = $manager->process($exception, $request);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals($result, $response);
    }

    public function testBuildHandlersSequence(): void
    {
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();

        $demoHandler = $this->getMockBuilder(HandlerInterface::class)->getMock();
        $runtimeHandler = $this->getMockBuilder(HandlerDecorator::class)->getMock();
        $unexpectedValueHandler = $this->getMockBuilder(HandlerDecorator::class)->getMock();

        $runtimeHandler->expects($this->once())
            ->method('wrapHandler')
            ->withConsecutive([$demoHandler]);

        $unexpectedValueHandler->expects($this->once())
            ->method('wrapHandler')
            ->withConsecutive([$runtimeHandler]);

        $handlers = $this->getMockBuilder(HandlersCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $handlersMap = [
            \Exception::class => $demoHandler,
            \RuntimeException::class => $runtimeHandler,
            \UnexpectedValueException::class => $unexpectedValueHandler,
        ];

        $handlers->expects($this->any())
            ->method('has')
            ->will($this->returnCallback(function ($val) use ($handlersMap) {
                return array_key_exists($val, $handlersMap);
            }));


        $handlers->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function ($val) use ($handlersMap) {
                return $handlersMap[$val];
            }));

        $manager = new ErrorManager($handlers);
        $manager->process(new \UnexpectedValueException(), $request);
    }
}
