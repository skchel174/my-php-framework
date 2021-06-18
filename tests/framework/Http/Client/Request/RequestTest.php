<?php

namespace Tests\framework\Http\Client\Request;

use Framework\Http\Client\Request\Exceptions\InvalidMethodException;
use Framework\Http\Client\Request\Request;
use Framework\Http\Client\Uri\UriFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class RequestTest extends TestCase
{
    public function testMethod(): void
    {
        $this->expectException(InvalidMethodException::class);
        $request = new Request();

        $request = $request->withMethod('GET');

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertNotEquals('POST', $request->getMethod());

        $request->withMethod('INVALID');
    }

    public function testUri(): void
    {
        $request = new Request();
        $host = 'host:8080';
        $uri = (new UriFactory)->createUri('https://' . $host);

        $request = $request->withUri($uri, true);

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertInstanceOf(UriInterface::class, $request->getUri());
        $this->assertEquals($uri, $request->getUri());

        $this->assertEmpty($request->getHeader('Host'));
        $this->assertNotEquals($request->getHeader('Host'),[$host]);

        $request = $request->withUri($uri);

        $this->assertNotEmpty($request->getHeader('Host'));
        $this->assertEquals($request->getHeader('Host'),[$host]);
    }

    public function testRequestTarget(): void
    {
        $request = new Request();
        $requestTarget = '/resource?param=value';
        $uri = (new UriFactory)->createUri('https://host' . $requestTarget);

        $requestWithUri = $request->withUri($uri);
        $requestWithAddedTarget = $request->withRequestTarget($requestTarget);

        $this->assertEquals('/', $request->getRequestTarget());
        $this->assertEquals($requestWithUri->getRequestTarget(), $requestWithAddedTarget->getRequestTarget());
        $this->assertInstanceOf(RequestInterface::class, $requestWithUri);
    }
}
