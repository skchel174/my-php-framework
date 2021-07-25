<?php

namespace Tests\framework\ErrorHandler;

use Framework\ErrorHandler\ErrorFactory\JsonErrorFactory;
use Framework\Http\Client\Response\JsonResponse;
use Framework\Renderer\Interfaces\RendererInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Whoops\Run;

class JsonErrorFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $config = ['debug' => false];

        $factory = new JsonErrorFactory();
        $response = $factory->create($exception = new \Exception('Exception message', 404));

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals($response->getStatusCode(), $exception->getCode());
        $this->assertNotEmpty($exception->getMessage());

        $this->assertStringContainsString($exception->getCode(), $response->getBody());
        $this->assertStringContainsString($exception->getMessage(), $response->getBody());
    }
}
