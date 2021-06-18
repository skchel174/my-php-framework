<?php

namespace Tests\framework\Http\Message\Client;

use Framework\Http\Client\Message\HttpMessage;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class HttpMessageTest extends TestCase
{
    private MessageInterface $message;

    public function setUp(): void
    {
        $this->message = new DummyHttpMessage();
    }

    public function testProtocolVersion(): void
    {
        $this->assertEmpty($this->message->getProtocolVersion());

        $message = $this->message->withProtocolVersion($protocol = '1.1');

        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertEquals($message->getProtocolVersion(), $protocol);
    }

    public function testGetEmptyHeader(): void
    {
        $this->assertEmpty($this->message->getHeaders());
        $this->assertIsArray($this->message->getHeaders());
    }

    public function testWithStringHeader(): void
    {
        $header = [
            'name' => 'Accept',
            'value' => 'text/html',
        ];

        $message = $this->message->withHeader($header['name'], $header['value']);

        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertIsArray($message->getHeader($header['name']));
        $this->assertEquals(
            [ $header['value'] ],
            $message->getHeader(strtolower($header['name']))
        );
    }

    public function testWithArrayHeader(): void
    {
        $header = [
            'name' => 'Content-Type',
            'value' => ['text/html', 'charset=UTF-8'],
        ];

        $message = $this->message->withHeader($header['name'], $header['value']);

        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertIsArray($message->getHeader($header['name']));
        $this->assertEquals($header['value'], $message->getHeader(strtolower($header['name'])));
    }

    public function testGetHeaderLine(): void
    {
        $header = [
            'name' => 'Content-Type',
            'value' => ['text/html', 'charset=UTF-8'],
        ];

        $message = $this->message->withHeader($header['name'], $header['value']);

        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertIsString($message->getHeaderLine($header['name']));
        $this->assertEquals(
            implode(',', $header['value']),
            $message->getHeaderLine(strtolower($header['name']))
        );
    }

    public function testWithAddedHeader(): void
    {
        $header = [
            'name' => 'Content-Type',
            'value1' => ['text/html'],
            'value2' => 'charset=UTF-8',
        ];

        $message = $this->message
            ->withAddedHeader($header['name'], $header['value1'])
            ->withAddedHeader($header['name'], $header['value2']);

        $this->assertInstanceOf(MessageInterface::class, $message);

        $this->assertEquals(
            array_merge($header['value1'], [ $header['value2'] ]),
            $message->getHeader(strtolower($header['name']))
        );
    }

    public function testWithoutHeader(): void
    {
        $header = [
            'name' => 'Content-Type',
            'value' => ['text/html', 'charset=UTF-8'],
        ];

        $message = $this->message->withHeader($header['name'], $header['value']);
        $this->assertNotEmpty($message->getHeader($header['name']));

        $message = $message->withoutHeader($header['name']);
        $this->assertEmpty($message->getHeader($header['name']));
    }

    public function testWithBody(): void
    {
        $body = $this->createMock(StreamInterface::class);

        $message = $this->message->withBody($body);

        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertInstanceOf(StreamInterface::class, $message->getBody());
        $this->assertEquals($body, $message->getBody());
    }
}

class DummyHttpMessage extends HttpMessage {}
