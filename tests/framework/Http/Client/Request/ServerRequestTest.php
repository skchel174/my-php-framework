<?php

namespace Tests\framework\Http\Client\Request;

use Framework\Http\Client\Request\Exceptions\InvalidBodyTypeException;
use Framework\Http\Client\Request\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequestTest extends TestCase
{
    public function testServerParams(): void
    {
        $server = ['HTTP_HOST' => 'localhost'];
        $request = new ServerRequest('', null, $server);

        $this->assertIsArray($request->getServerParams());
        $this->assertNotEmpty($request->getServerParams());

        $this->assertEquals($server, $request->getServerParams());
        $this->assertArrayHasKey('HTTP_HOST', $request->getServerParams());
        $this->assertContains($server['HTTP_HOST'], $request->getServerParams());
    }

    public function testCookieParams(): void
    {
        $request = new ServerRequest();
        $cookie = ['name' => 'value'];

        $this->assertEmpty($request->getCookieParams());
        $this->assertIsArray($request->getCookieParams());

        $requestWithCookie = $request->withCookieParams($cookie);

        $this->assertEquals($cookie, $requestWithCookie->getCookieParams());
        $this->assertInstanceOf(ServerRequestInterface::class, $requestWithCookie);
    }

    public function testQueryParams(): void
    {
        $request = new ServerRequest();
        $query = ['param' => 'value'];

        $this->assertEmpty($request->getQueryParams());
        $this->assertIsArray($request->getQueryParams());

        $requestWithQuery = $request->withQueryParams($query);

        $this->assertEquals($query, $requestWithQuery->getQueryParams());
        $this->assertInstanceOf(ServerRequestInterface::class, $requestWithQuery);
    }

    public function testUploadedFiles(): void
    {
        $request = new ServerRequest();
        $files = [
            'file' => [
                'name' => 'image.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/phpn3FmFr',
                'error' => 0,
                'size' => 15476,
            ]
        ];

        $this->assertEmpty($request->getUploadedFiles());
        $this->assertIsArray($request->getUploadedFiles());

        $requestWithFiles = $request->withUploadedFiles($files);

        $this->assertEquals($files, $requestWithFiles->getUploadedFiles());
        $this->assertInstanceOf(ServerRequestInterface::class, $requestWithFiles);
    }

    public function testParsedBody(): void
    {
        $this->expectException(InvalidBodyTypeException::class);

        $request = new ServerRequest();
        $parsedBody = ['param' => 'value'];

        $this->assertNull($request->getParsedBody());

        $requestWithBody = $request->withParsedBody($parsedBody);

        $this->assertEquals($parsedBody, $requestWithBody->getParsedBody());
        $this->assertInstanceOf(ServerRequestInterface::class, $requestWithBody);

        $request->withParsedBody('string');
    }

    public function  testAttributes(): void
    {
        $request = new ServerRequest();
        $attribute = 'attribute';

        $this->assertNull($request->getAttribute('name'));
        $this->assertEquals('default', $request->getAttribute('name', 'default'));

        $requestWithAttributes = $request->withAttribute('name', $attribute);
        $this->assertEquals($attribute, $requestWithAttributes->getAttribute('name'));

        $requestWithAttributes = $requestWithAttributes->withoutAttribute('name');
        $this->assertNull($request->getAttribute('name'));

        $this->assertInstanceOf(ServerRequestInterface::class, $requestWithAttributes);
    }
}
