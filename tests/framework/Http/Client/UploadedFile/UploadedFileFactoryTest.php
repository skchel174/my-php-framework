<?php

namespace Tests\framework\Http\Client\UploadedFile;

use Framework\Http\Client\Stream\StreamFactory;
use Framework\Http\Client\UploadedFile\UploadedFileFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

class UploadedFileFactoryTest extends TestCase
{
    protected StreamInterface $stream;

    public function setUp(): void
    {
        $this->stream = (new StreamFactory)->createStream();
    }

    public function testCreateUploadedFile(): void
    {
        $file = (new UploadedFileFactory)->createUploadedFile(
            $this->stream,
            $size = $this->stream->getSize(),
            $error = \UPLOAD_ERR_OK,
            $fileName = 'uploaded.txt',
            $fileType = 'text/plain',
        );

        $this->assertInstanceOf(UploadedFileInterface::class, $file);

        $this->assertEquals($this->stream, $file->getStream());
        $this->assertEquals($size, $file->getSize());
        $this->assertEquals($error, $file->getError());
        $this->assertEquals($fileName, $file->getClientFilename());
        $this->assertEquals($fileType, $file->getClientMediaType());
    }

    public function testEmpty(): void
    {
        $file = (new UploadedFileFactory)->createUploadedFile($this->stream);

        $this->assertInstanceOf(UploadedFileInterface::class, $file);

        $this->assertEquals($this->stream, $file->getStream());
        $this->assertNull($file->getSize());
        $this->assertEquals(\UPLOAD_ERR_OK, $file->getError());
        $this->assertNull($file->getClientFilename());
        $this->assertNull($file->getClientMediaType());
    }
}
