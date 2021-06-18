<?php

namespace Tests\framework\Http\Client\Response;

use Framework\Http\Client\Request\Exceptions\InvalidUriTypeException;
use Framework\Http\Client\Response\RedirectResponse;
use Framework\Http\Client\Uri\UriFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class RedirectResponseTest extends TestCase
{
    public function testConstructWithStringUri(): void
    {
        $response = new RedirectResponse($uri = 'http://localhost/path/to/resource');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals($uri, $response->getHeaderLine('Location'));
    }

    public function testConstructWithUriObject(): void
    {
        $response = new RedirectResponse((new UriFactory)->createUri($uri = 'http://localhost/path/to/resource'));

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals($uri, $response->getHeaderLine('Location'));
    }

    public function testConstructFailed(): void
    {
        $this->expectException(InvalidUriTypeException::class);
        new RedirectResponse(new \StdClass());
    }
}
