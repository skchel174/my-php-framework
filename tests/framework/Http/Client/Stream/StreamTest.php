<?php

namespace Tests\framework\Http\Client\Stream;

use Framework\Http\Client\Stream\Stream;
use PHPUnit\Framework\TestCase;

class StreamTest extends TestCase
{
    /** @var null|resource $stream */
    private $stream;
    private string $content = 'Stream content';

    public function setUp(): void
    {
        $resource = fopen('php://memory', 'w+b');
        $this->stream = new Stream($resource);
    }

    public function testConstructFail(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Stream('invalid resource');
    }

    public function testParseToString(): void
    {
        $this->assertIsString((string)$this->stream);
        $this->assertEmpty((string)$this->stream);

        $this->stream->write($this->content);

        $this->assertEquals($this->content, (string)$this->stream);

        $this->stream->detach();

        $this->assertIsString((string)$this->stream);
        $this->assertEmpty((string)$this->stream);
    }

    public function testIsWritable(): void
    {
        $this->assertTrue($this->stream->isWritable());

        $this->stream->attach(fopen('php://memory', 'r'));

        $this->assertFalse($this->stream->isWritable());
    }

    public function testIsReadable(): void
    {
        $this->assertTrue($this->stream->isReadable());

        $this->stream->attach(fopen('php://output', 'w'));

        $this->assertFalse($this->stream->isReadable());
    }

    public function testGetSize(): void
    {
        $this->stream->write($this->content);

        $this->assertEquals(strlen($this->content), $this->stream->getSize());
    }

    public function testSeek(): void
    {
        $this->stream->write($this->content);
        $this->assertEquals(strlen($this->content), $this->stream->tell());

        $this->stream->seek(0);
        $this->assertEquals(0, $this->stream->tell());

        $offset = 7;
        $this->stream->seek($offset);
        $this->assertEquals(substr($this->content, $offset), $this->stream->read(strlen($this->content) - $offset));
    }

    public function testClose(): void
    {
        $this->stream->write($this->content);
        $this->assertEquals($this->content, (string)$this->stream);

        $this->stream->close();

        $this->assertFalse($this->stream->isReadable());
        $this->assertNotEquals($this->content, (string)$this->stream);
        $this->assertEquals('', (string)$this->stream);
    }

    public function testGetMetadata(): void
    {
        $this->assertIsArray($this->stream->getMetadata());
        $this->assertNotEmpty($this->stream->getMetadata());

        $this->assertArrayHasKey('uri', $this->stream->getMetadata());
        $this->assertEquals('php://memory', $this->stream->getMetadata('uri'));
        $this->assertEquals('w+b', $this->stream->getMetadata('mode'));
    }
}
