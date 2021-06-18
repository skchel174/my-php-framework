<?php

namespace Tests\framework\Http\Client\Response;

use Framework\Http\Client\Stream\StreamFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class JsonResponse extends TestCase
{
    public function testHeader(): void
    {
        $response = new JsonResponse('');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('application/json; charset=utf-8', $response->getHeaderLine('Content-Type'));
    }

    public function testConstructWithStringBody(): void
    {
        $response = new JsonResponse($body = 'Response content');

        $this->assertInstanceOf(StreamInterface::class, $response->getBody());
        $this->assertEquals(json_encode($body), $response->getBody());
    }

    public function testConstructWithArrayBody(): void
    {
        $response = new JsonResponse($body = ['name' => 'John', 'age' => 28]);

        $this->assertInstanceOf(StreamInterface::class, $response->getBody());
        $this->assertEquals(json_encode($body), $response->getBody());
    }

    public function testConstructWithObjectBody(): void
    {
        $obj = new \stdClass();
        $obj->name = 'John';
        $obj->age = 28;
        $response = new JsonResponse($obj);

        $this->assertInstanceOf(StreamInterface::class, $response->getBody());
        $this->assertEquals(json_encode($obj), $response->getBody());
    }

    public function testConstructWithStreamBody(): void
    {
        $response = new JsonResponse((new StreamFactory)->createStream($body = 'Response content'));

        $this->assertInstanceOf(StreamInterface::class, $response->getBody());
        $this->assertEquals(json_encode($body), $response->getBody());
    }

    public function testConstructWithEmptyBody(): void
    {
        $response = new JsonResponse($body = null);

        $this->assertInstanceOf(StreamInterface::class, $response->getBody());
        $this->assertEquals(json_encode((string)$body), $response->getBody());
    }
}
