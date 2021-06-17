<?php

namespace Tests\framework\Http\Client\UploadedFile;

use Framework\Http\Client\Stream\StreamFactory;
use Framework\Http\Client\UploadedFile\Exceptions\AlreadyMovedFileException;
use Framework\Http\Client\UploadedFile\Exceptions\InvalidPathTypeException;
use Framework\Http\Client\UploadedFile\UploadedFile;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class UploadedFileTest extends TestCase
{
    protected string $tmpDir = __DIR__ . '/tmp';
    protected string $moveDir = __DIR__ . '/move';
    protected string $fileName = 'uploaded.txt';
    protected string $fileType = 'text/plain';
    protected string $fileContent = 'File content';
    protected int $error = \UPLOAD_ERR_OK;
    protected UploadedFile $file;

    public function setUp(): void
    {
        if (!file_exists($this->tmpDir)) {
            mkdir($this->tmpDir);
        }

        if (!file_exists($this->moveDir)) {
            mkdir($this->moveDir);
        }

        file_put_contents($this->tmpDir . '/' . $this->fileName, $this->fileContent);

        $stream = (new StreamFactory)->createStreamFromFile($this->tmpDir . '/' . $this->fileName);
        $this->file = new UploadedFile(
            $stream,
            $stream->getSize(),
            $this->error,
            $this->fileName,
            $this->fileType
        );
    }

    public function tearDown(): void
    {
        if (file_exists($this->tmpDir)) {
            array_map('unlink', glob($this->tmpDir . '/*'));
            rmdir($this->tmpDir);
        }

        if (file_exists($this->moveDir)) {
            array_map('unlink', glob($this->moveDir . '/*'));
            rmdir($this->moveDir);
        }
    }

    public function testUpload(): void
    {
        $this->assertFileExists($this->tmpDir . '/' . $this->fileName);
        $this->file->getStream()->close();
    }

    public function testMove(): void
    {
        $this->file->moveTo($this->moveDir . '/' . $this->fileName);
        $this->assertFileExists($this->moveDir . '/' . $this->fileName);
        $this->assertEquals($this->fileContent, file_get_contents($this->moveDir . '/' . $this->fileName));
    }

    public function testMoveToInvalidTarget(): void
    {
        $this->expectException(InvalidPathTypeException::class);
        $this->file->getStream()->close();
        $this->file->moveTo(1);
    }

    public function testMoveTheMovedFile(): void
    {
        $this->expectException(AlreadyMovedFileException::class);
        $this->file->moveTo($this->moveDir . '/' . $this->fileName);
        $this->file->moveTo($this->moveDir . '/' . $this->fileName);
        $this->file->getStream()->close();
    }

    public function testGetStream(): void
    {
        $stream = $this->file->getStream();

        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->file->getStream()->close();
    }

    public function testGetStreamFromMovedFile(): void
    {
        $this->expectException(AlreadyMovedFileException::class);
        $this->file->moveTo($this->moveDir . '/' . $this->fileName);
        $this->file->getStream();
    }

    public function testGetSize(): void
    {
        $this->assertEquals($this->file->getStream()->getSize(), $this->file->getSize());
        $this->file->getStream()->close();
    }

    public function testGetError(): void
    {
        $this->assertEquals($this->error, $this->file->getError());
        $this->file->getStream()->close();
    }

    public function testGetClientFilename(): void
    {
        $this->assertEquals($this->fileName, $this->file->getClientFilename());
        $this->file->getStream()->close();
    }

    public function testGetClientMediaType(): void
    {
        $this->assertEquals($this->fileType, $this->file->getClientMediaType());
        $this->file->getStream()->close();
    }
}
