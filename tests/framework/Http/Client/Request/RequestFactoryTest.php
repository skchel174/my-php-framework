<?php

namespace Tests\framework\Http\Client\Request;

use Framework\Http\Client\Request\Exceptions\InvalidMethodException;
use Framework\Http\Client\Request\Exceptions\InvalidUriTypeException;
use Framework\Http\Client\Request\RequestFactory;
use Framework\Http\Client\Uri\Uri;
use Framework\Http\Client\Uri\UriFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class RequestFactoryTest extends TestCase
{
    public function testInvalidMethod(): void
    {
        $this->expectException(InvalidMethodException::class);
        (new RequestFactory)->createRequest('INVALID', new Uri());
    }

    public function testInvalidUri(): void
    {
        $this->expectException(InvalidUriTypeException::class);
        (new RequestFactory)->createRequest('GET', new \stdClass());
    }

    public function testCreateRequest(): void
    {
        $factory = new RequestFactory();
        $method = 'GET';
        $uriString = 'https://host:8080/';
        $uri = (new UriFactory)->createUri($uriString);

        $requestFromUriString = $factory->createRequest($method, $uriString);
        $requestFromUriObject = $factory->createRequest($method, $uri);

        $this->assertInstanceOf(RequestInterface::class, $requestFromUriString);
        $this->assertInstanceOf(RequestInterface::class, $requestFromUriObject);

        $this->assertEquals($method, $requestFromUriString->getMethod());
        $this->assertEquals($method, $requestFromUriObject->getMethod());

        $this->assertInstanceOf(UriInterface::class, $requestFromUriString->getUri());
        $this->assertEquals($uriString, (string)$requestFromUriString->getUri());
        $this->assertEquals($uriString, (string)$requestFromUriObject->getUri());
    }
}
