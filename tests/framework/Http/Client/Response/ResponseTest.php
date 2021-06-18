<?php

namespace Tests\framework\Http\Client\Response;

use Framework\Http\Client\Response\Exceptions\InvalidCodeException;
use Framework\Http\Client\Response\Response;
use Framework\Http\Client\Stream\StreamFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResponseTest extends TestCase
{
    public function testConstructWithStringBody(): void
    {
        $response = new Response($body = 'response content');

        $this->assertInstanceOf(StreamInterface::class, $response->getBody());
        $this->assertEquals($body, (string)$response->getBody());
    }

    public function testConstructWithStreamBody(): void
    {
        $stream = (new StreamFactory)->createStream($body = 'response content');
        $response = new Response($stream);

        $this->assertInstanceOf(StreamInterface::class, $response->getBody());
        $this->assertEquals($body, (string)$response->getBody());
    }

    public function testConstructWithEmptyBody(): void
    {
        $response = new Response();
        $this->assertEquals(null, $response->getBody());
    }

    public function testStatusCode(): void
    {
        $response = (new Response)->withStatus($code = 404);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals($code, $response->getStatusCode());
    }

    public function testInvalidStatusCode(): void
    {
        $this->expectException(InvalidCodeException::class);
        (new Response)->withStatus(99);
    }

    public function testReasonPhrase(): void
    {
        $response = (new Response)->withStatus($code = 404);

        $this->assertEquals(Response::REASON_PHRASES[$code], $response->getReasonPhrase());

        $response = (new Response)->withStatus($code = 404, $phrase = 'Reason phrase');

        $this->assertNotEquals(Response::REASON_PHRASES[$code], $response->getReasonPhrase());
        $this->assertEquals($phrase, $response->getReasonPhrase());
    }
}
