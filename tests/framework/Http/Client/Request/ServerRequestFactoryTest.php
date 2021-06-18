<?php

namespace Tests\framework\Http\Client\Request;

use Framework\Http\Client\Request\Exceptions\InvalidMethodException;
use Framework\Http\Client\Request\Exceptions\InvalidUriTypeException;
use Framework\Http\Client\Request\RequestFactory;
use Framework\Http\Client\Request\ServerRequestFactory;
use Framework\Http\Client\Stream\StreamFactory;
use Framework\Http\Client\Uri\Uri;
use Framework\Http\Client\Uri\UriFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

class ServerRequestFactoryTest extends TestCase
{
    protected array $server = [
        'SERVER_PROTOCOL' => 'HTTP/1.1',
        'REQUEST_URI' => '/?name=John1&age=28',
        'REQUEST_METHOD' => 'POST',
        'HTTP_HOST' => 'localhost',
        'HTTP_ACCEPT_LANGUAGE' => 'ru-RU,ru',
    ];

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

    public function testCreateServerRequest(): void
    {
        $factory = new ServerRequestFactory();
        $method = 'GET';
        $uriString = 'https://localhost:8080/';
        $uri = (new UriFactory)->createUri($uriString);

        $requestFromUriString = $factory->createServerRequest($method, $uriString);
        $requestFromUriObject = $factory->createServerRequest($method, $uri);

        $this->assertInstanceOf(ServerRequestInterface::class, $requestFromUriString);
        $this->assertInstanceOf(ServerRequestInterface::class, $requestFromUriObject);

        $this->assertEquals($method, $requestFromUriString->getMethod());
        $this->assertEquals($method, $requestFromUriObject->getMethod());

        $this->assertInstanceOf(UriInterface::class, $requestFromUriString->getUri());
        $this->assertEquals($uriString, (string)$requestFromUriString->getUri());
        $this->assertEquals($uriString, (string)$requestFromUriObject->getUri());
    }

    public function testRequestWithServerParams()
    {
        $factory = new ServerRequestFactory();
        $request = $factory->createFromSapi($this->server);

        $this->assertInstanceOf(ServerRequestInterface::class, $request);

        $this->assertNotEmpty($request->getServerParams());

        $this->assertEquals('1.1', $request->getProtocolVersion());
        $this->assertEquals($this->server['REQUEST_METHOD'], $request->getMethod());
        $this->assertEquals($this->server['REQUEST_URI'], $request->getRequestTarget());

        $this->assertEquals($this->server['HTTP_HOST'], $request->getHeaderLine('Host'));
        $this->assertEquals($this->server['HTTP_ACCEPT_LANGUAGE'], $request->getHeaderLine('Accept-Language'));
    }

    public function testRequestWithCookie(): void
    {
        $factory = new ServerRequestFactory();
        $cookie = [ 'PHPSESSID' => 'vd0ov5hoobgl7rgij9s9ns5pb0' ];
        $request = $factory->createFromSapi($this->server, $cookie);

        $this->assertInstanceOf(ServerRequestInterface::class, $request);

        $this->assertNotEmpty($request->getCookieParams());
        $this->assertEquals($cookie, $request->getCookieParams());

        $this->assertArrayHasKey('PHPSESSID', $request->getCookieParams());
        $this->assertContains($cookie['PHPSESSID'], $request->getCookieParams());
    }

    public function testGetRequest()
    {
        $factory = new ServerRequestFactory();
        $query = [ 'name' => 'John', 'age' => 28 ];
        $request = $factory->createFromSapi($this->server, [], $query);

        $this->assertInstanceOf(ServerRequestInterface::class, $request);

        $this->assertNotEmpty($request->getQueryParams());
        $this->assertEquals($query, $request->getQueryParams());

        $this->assertArrayHasKey('name', $request->getQueryParams());
        $this->assertContains($query['name'], $request->getQueryParams());

        $this->assertArrayHasKey('age', $request->getQueryParams());
        $this->assertContains($query['age'], $request->getQueryParams());
    }

    public function testPostRequest()
    {
        $factory = new ServerRequestFactory();
        $parsedBody = [ 'name' => 'John', 'age' => 28 ];
        $request = $factory->createFromSapi($this->server, [], [], $parsedBody);

        $this->assertInstanceOf(ServerRequestInterface::class, $request);

        $this->assertNotEmpty($request->getParsedBody());
        $this->assertEquals($parsedBody, $request->getParsedBody());

        $this->assertArrayHasKey('name', $request->getParsedBody());
        $this->assertContains($parsedBody['name'], $request->getParsedBody());

        $this->assertArrayHasKey('age', $request->getParsedBody());
        $this->assertContains($parsedBody['age'], $request->getParsedBody());
    }

    public function testFileUploadRequest()
    {
        $factory = new ServerRequestFactory();
        $files = [
            'file' => [
                'name' => 'file.txt',
                'type' => 'text/plain',
                'tmp_name' => 'php://memory',
                'error' => 0,
                'size' => 9393,
            ],
        ];
        $request = $factory->createFromSapi($this->server, [], [], [], $files);

        $this->assertInstanceOf(ServerRequestInterface::class, $request);

        $this->assertNotEmpty($request->getUploadedFiles());
        $this->assertContainsOnlyInstancesOf(UploadedFileInterface::class, $request->getUploadedFiles());
    }

    public function testFilesUploadRequest()
    {
        $factory = new ServerRequestFactory();
        $files = [
            'files' => [
                'name' => [ 'img_1.png', 'img_2.png' ],
                'type' => [ 'image/png', 'image/png' ],
                'tmp_name' => [ 'php://memory', 'php://memory' ],
                'error' => [ 0, 0 ],
                'size' => [ 481517, 671896 ],
            ],
        ];
        $request = $factory->createFromSapi($this->server, [], [], [], $files);

        $this->assertInstanceOf(ServerRequestInterface::class, $request);

        $this->assertNotEmpty($request->getUploadedFiles());
        $this->assertContainsOnlyInstancesOf(UploadedFileInterface::class, $request->getUploadedFiles());
        $this->assertCount(count($files['files']['name']), $request->getUploadedFiles());
    }

    public function testJsonRequest()
    {
        $factory = new ServerRequestFactory();
        $data = '{"name":"John","age":28}';
        $server = array_merge($this->server, ['HTTP_CONTENT_TYPE' => 'application/json']);

        $request = $factory->createFromSapi($server, [], [], [], [], (new StreamFactory)->createStream($data));

        $this->assertInstanceOf(ServerRequestInterface::class, $request);

        $this->assertInstanceOf(StreamInterface::class, $request->getBody());
        $this->assertEquals($data, $request->getBody()->getContents());
        $this->assertEquals(json_decode($data, true), $request->getParsedBody());
    }
}
