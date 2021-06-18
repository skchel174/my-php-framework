<?php

namespace Tests\framework\Http\Client\Uri;

use Framework\Http\Client\Uri\UriFactory;
use PHPUnit\Framework\TestCase;

class UriFactoryTest extends TestCase
{
    public function testEmpty(): void
    {
        $uri = (new UriFactory)->createUri();
        $this->assertEquals('', $uri->getScheme());
        $this->assertEquals('', $uri->getUserInfo());
        $this->assertEquals('', $uri->getHost());
        $this->assertNull($uri->getPort());
        $this->assertEquals('', $uri->getPath());
        $this->assertEquals('', $uri->getQuery());
        $this->assertEquals('', $uri->getFragment());
    }

    /**
     * @dataProvider uriStringProvider
     */
    public function testCreateUri($uri): void
    {
        $obj = (new UriFactory)->createUri($uri);
        $this->assertContains([(string)$obj], $this->uriStringProvider());
    }

    public function uriStringProvider(): array
    {
        return [
            ['http://localhost/'],
            ['http://localhost:8080/path/to/resource'],
            ['https://127.0.0.1/path/to/resource'],
            ['https://user@localhost/path/to/resource'],
            ['https://user:password@localhost/path/to/resource'],
            ['https://user:password@localhost/?param1=value1&param2=value2'],
            ['https://user:password@localhost/path/to/resource#fragment'],
            ['https://user:password@localhost/path/to/resource?param1=value1&param2=value2#fragment'],
        ];
    }

    /**
     * @dataProvider uriArrayProvider
     */
    public function testCreateUriFromSapi($uri): void
    {
        $obj = (new UriFactory)->createUriFromSapi($uri);
        $this->assertArrayHasKey((string)$obj, $this->uriArrayProvider());
    }

    public function uriArrayProvider(): array
    {
        return [
            'http://localhost/path/to/resource' => [[
                'SERVER_PROTOCOL' => 'HTTP/1.1',
                'HTTP_HOST' => 'localhost',
                'SERVER_PORT' => '80',
                'REQUEST_URI' => '/path/to/resource'
            ]],
            'https://localhost/path/to/resource' => [[
                'SERVER_PROTOCOL' => 'HTTPS/1.1',
                'HTTP_HOST' => 'localhost',
                'SERVER_PORT' => '443',
                'REQUEST_URI' => '/path/to/resource'
            ]],
            'https://127.0.0.1:65037/' => [[
                'SERVER_PROTOCOL' => 'HTTPS/1.1',
                'HTTP_HOST' => '127.0.0.1',
                'SERVER_PORT' => '65037',
                'REQUEST_URI' => '/'
            ]],
            'http://user:password@localhost/index.php?param1=value1&param2=value2' => [[
                'SERVER_PROTOCOL' => 'HTTP/1.1',
                'PHP_AUTH_USER' => 'user',
                'PHP_AUTH_PW' => 'password',
                'HTTP_HOST' => 'localhost',
                'SERVER_PORT' => '80',
                'REQUEST_URI' => '/index.php?param1=value1&param2=value2#fragment'
            ]],
            'https://localhost/index.php' => [[
                'SERVER_PROTOCOL' => 'HTTPS/1.1',
                'HTTP_HOST' => 'localhost',
                'SERVER_PORT' => '443',
                'REQUEST_URI' => '/index.php#fragment'
            ]],
        ];
    }
}
