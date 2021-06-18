<?php

namespace Tests\framework\Http\Client\Response;

use Framework\Http\Client\Message\Exceptions\InvalidBodyTypeException;
use Framework\Http\Client\Response\HtmlResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HtmlResponseTest extends TestCase
{
    public function testConstruct(): void
    {
        $response = new HtmlResponse($body = 'Response content');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(StreamInterface::class, $response->getBody());
        $this->assertEquals('text/html; charset=utf-8', $response->getHeaderLine('Content-Type'));
        $this->assertEquals($body, $response->getBody());
    }

    public function testConstructFailed(): void
    {
        $this->expectException(InvalidBodyTypeException::class);
        new HtmlResponse([]);
    }
}
