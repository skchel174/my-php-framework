<?php

namespace Tests\framework\ErrorHandler;

use Framework\ErrorHandler\ErrorFactory\ErrorFactory;
use Framework\Http\Client\Response\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ErrorFactoryTest extends TestCase
{
    public function testNormalizeStandardCode(): void
    {
        $exception = new \Exception('', 404);

        $factory = new DummyErrorFactory();
        $response = $factory->create($exception);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals($exception->getCode(), $response->getStatusCode());
    }

    public function testNormalizeNotStandardCode(): void
    {
        $exception = new \Exception('', 1022);

        $factory = new DummyErrorFactory();
        $response = $factory->create($exception);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertNotEquals($exception->getCode(), $response->getStatusCode());
        $this->assertEquals(500, $response->getStatusCode());
    }
}

class DummyErrorFactory extends ErrorFactory
{
    public function create(\Throwable $e): ResponseInterface
    {
        $code = $this->normalizeCode($e->getCode());
        return new Response($e->getMessage(), $code);
    }
}
