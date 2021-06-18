<?php

namespace Tests\framework\Http\Client\Response;

use Framework\Http\Client\Response\Response;
use Framework\Http\Client\Response\ResponseFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ResponseFactoryTest extends TestCase
{
    public function testCreateResponse(): void
    {
        $response = (new ResponseFactory)->createResponse($code = 201);

        $this->assertInstanceOf(ResponseInterface::class, $response);

        $this->assertNull($response->getBody());
        $this->assertEquals($code, $response->getStatusCode());
        $this->assertEquals(Response::REASON_PHRASES[$code], $response->getReasonPhrase());
    }
}
