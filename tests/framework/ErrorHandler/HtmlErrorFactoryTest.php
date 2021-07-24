<?php

namespace Tests\framework\ErrorHandler;

use Framework\ErrorHandler\ErrorFactory\HtmlErrorFactory;
use Framework\Http\Client\Response\HtmlResponse;
use Framework\Renderer\Interfaces\RendererInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Whoops\Run;

class HtmlErrorFactoryTest extends TestCase
{
    public function testDebugCreate()
    {
        $config = ['debug' => true];
        $renderer = $this->getMockBuilder(RendererInterface::class)->getMock();
        $exception = new \Exception('Exception message', 404);

        $factory = new HtmlErrorFactory($renderer, new Run(), $config);
        $response = $factory->create($exception);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(HtmlResponse::class, $response);

        $this->assertEquals($response->getStatusCode(), $exception->getCode());
        $this->assertStringContainsString($response->getBody(), $exception->getMessage());
    }

    public function testProductionCreate(): void
    {
        $config = [
            'debug' => false,
            'error' => ['templates' => ['404' => '404.phtml']],
        ];
        $renderer = $this->getMockBuilder(RendererInterface::class)->getMock();
        $renderer->expects($this->once())
            ->method('render')
            ->withConsecutive([$config['error']['templates']['404']])
            ->willReturn($message = 'Exception message');

        $factory = new HtmlErrorFactory($renderer, new Run(), $config);
        $response = $factory->create($exception = new \Exception($message, 404));

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(HtmlResponse::class, $response);

        $this->assertEquals($response->getStatusCode(), $exception->getCode());
        $this->assertStringContainsString($response->getBody(), $exception->getMessage());
    }
}
