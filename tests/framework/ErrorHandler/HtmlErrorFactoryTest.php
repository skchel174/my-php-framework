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
    public function testCreate(): void
    {
        $config = ['404' => '404.phtml'];
        $renderer = $this->getMockBuilder(RendererInterface::class)->getMock();
        $renderer->expects($this->once())
            ->method('render')
            ->withConsecutive([$config['404']])
            ->willReturn($message = 'Exception message');

        $factory = new HtmlErrorFactory($renderer, $config);
        $response = $factory->create($exception = new \Exception($message, 404));

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(HtmlResponse::class, $response);

        $this->assertEquals($response->getStatusCode(), $exception->getCode());
        $this->assertStringContainsString($response->getBody(), $exception->getMessage());
    }
}
