<?php

namespace Tests\framework\Http\Client\Stream;

use Framework\Http\Client\Stream\Exceptions\InvalidStreamModeException;
use Framework\Http\Client\Stream\Exceptions\NotOpeningFileException;
use Framework\Http\Client\Stream\StreamFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class StreamFactoryTest extends TestCase
{
    public function createStreamTest(): void
    {
        $content = 'Stream content';
        $stream = (new StreamFactory)->createStream($content);

        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertEquals($content, (string)$stream);
    }

    public function testCreateStreamFromFile(): void
    {
        $stream = (new StreamFactory)->createStreamFromFile('php://memory');
        $this->assertInstanceOf(StreamInterface::class, $stream);
    }

    public function testCreateStreamFromFileWithInvalidMode(): void
    {
        $this->expectException(InvalidStreamModeException::class);
        (new StreamFactory)->createStreamFromFile('php://memory', 's');
    }

    public function testCreateStreamFromInvalidFile(): void
    {
        $this->expectException(NotOpeningFileException::class);
        (new StreamFactory)->createStreamFromFile('');
    }

    public function createStreamFRomResourceTest(): void
    {
        $resource = fopen('php://memory', 'w+b');
        $stream = (new StreamFactory)->createStreamFromResource($resource);

        $this->assertInstanceOf(StreamInterface::class, $stream);
    }
}
