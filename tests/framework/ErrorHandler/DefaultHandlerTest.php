<?php

namespace Tests\framework\ErrorHandler;

use Framework\ErrorHandler\DefaultHandler;
use Framework\ErrorHandler\ErrorFactory\HtmlErrorFactory;
use Framework\ErrorHandler\ErrorFactory\JsonErrorFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class DefaultHandlerTest extends TestCase
{
    public function testRenderHtmlError(): void
    {
        $exception = new \Exception();
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();

        $request->expects($this->once())
            ->method('getHeaderLine')
            ->withConsecutive(['Accept'])
            ->willReturn('text/html');

        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();

        $htmlErrorFactory = $this->getMockBuilder(HtmlErrorFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $htmlErrorFactory->expects($this->once())
            ->method('create')
            ->withConsecutive([$exception])
            ->willReturn($response);

        $jsonErrorFactory = $this->getMockBuilder(JsonErrorFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $jsonErrorFactory->expects($this->never())
            ->method('create');

        $handler = new DefaultHandler($logger, $htmlErrorFactory, $jsonErrorFactory);
        $handler->render($exception, $request);
    }

    public function testRenderJsonError(): void
    {
        $exception = new \Exception();
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();

        $request->expects($this->once())
            ->method('getHeaderLine')
            ->withConsecutive(['Accept'])
            ->willReturn('application/json');

        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();

        $htmlErrorFactory = $this->getMockBuilder(HtmlErrorFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $htmlErrorFactory->expects($this->never())
            ->method('create');

        $jsonErrorFactory = $this->getMockBuilder(JsonErrorFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $jsonErrorFactory->expects($this->once())
            ->method('create')
            ->withConsecutive([$exception])
            ->willReturn($response);

        $handler = new DefaultHandler($logger, $htmlErrorFactory, $jsonErrorFactory);
        $handler->render($exception, $request);
    }
}
