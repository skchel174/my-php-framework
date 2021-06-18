<?php

namespace Tests\framework\Http\Client\Uri;

use Framework\Http\Client\Uri\Exceptions\InvalidUriSchemeException;
use Framework\Http\Client\Uri\Exceptions\UriPortRangeException;
use Framework\Http\Client\Uri\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

class UriTest extends TestCase
{
    protected UriInterface $uri;

    public function setUp(): void
    {
        $this->uri = new Uri();
    }

    public function testScheme(): void
    {
        $uri = $this->uri->withScheme($scheme = 'http');

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertEquals($scheme, $uri->getScheme());
    }

    public function testInvalidScheme(): void
    {
        $this->expectException(InvalidUriSchemeException::class);
        $this->uri->withScheme('ftp');
    }

    public function testAuthority(): void
    {
        $uri = $this->uri
            ->withUserInfo('user', 'password')
            ->withHost('localhost')
            ->withPort(8080);

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertEquals('user:password@localhost:8080', $uri->getAuthority());
    }

    public function testUserInfo(): void
    {
        $uri = $this->uri->withUserInfo('user', 'password');

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertEquals('user:password', $uri->getUserInfo());

        $uri = $this->uri->withUserInfo($userInfo = rawurlencode(Uri::URI_GEN_DELIMS));

        $this->assertEquals($userInfo, $uri->getUserInfo());
    }

    public function testHost(): void
    {
        $uri = $this->uri->withHost($host = 'localhost');

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertEquals($host, $uri->getHost());

        $uri = $this->uri->withHost($host = rawurlencode(Uri::URI_GEN_DELIMS));

        $this->assertEquals($host, $uri->getHost());
    }

    public function testPort(): void
    {
        $uri = $this->uri->withPort($port = 8080);

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertEquals($port, $uri->getPort());

        $uri = $this->uri->withPort('8080');

        $this->assertIsInt($uri->getPort());

        $uri = $this->uri
            ->withScheme('https')
            ->withPort($port = 443);

        $this->assertNull($uri->getPort());
    }

    public function testInvalidMinRangePort(): void
    {
        $this->expectException(UriPortRangeException::class);
        $this->uri->withPort(-1);
    }

    public function testInvalidMaxRangePort(): void
    {
        $this->expectException(UriPortRangeException::class);
        $this->uri->withPort(65536);
    }

    public function testPath(): void
    {
        $uri = $this->uri->withPath($path = '/path/to/resource');

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertEquals($path, $uri->getPath());

        $uri = $this->uri->withPath($path = 'path/to/resource');

        $this->assertEquals('/' . $path, $uri->getPath());

        $uri = $this->uri->withPath($path = '/' . rawurlencode(Uri::URI_GEN_DELIMS));

        $this->assertEquals($path, $uri->getPath());
    }

    public function testQuery(): void
    {
        $uri = $this->uri->withQuery($query = 'param1=value1&param2=value2');

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertEquals($query, $uri->getQuery());

        $uri = $this->uri->withQuery($query = 'param1=' . rawurlencode(Uri::URI_GEN_DELIMS));

        $this->assertEquals($query, $uri->getQuery());
    }

    public function testFragment(): void
    {
        $uri = $this->uri->withFragment($fragment = 'fragment');

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertEquals($fragment, $uri->getFragment());

        $uri = $this->uri->withFragment($fragment = rawurlencode(Uri::URI_GEN_DELIMS));

        $this->assertEquals($fragment, $uri->getFragment());
    }
}
