<?php

namespace Framework\Http\Client\UploadedFile;

use Framework\Http\Client\Stream\StreamFactory;
use Framework\Http\Client\UploadedFile\Exceptions\AlreadyMovedFileException;
use Framework\Http\Client\UploadedFile\Exceptions\FileMoveFailException;
use Framework\Http\Client\UploadedFile\Exceptions\InvalidPathTypeException;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

class UploadedFile implements UploadedFileInterface
{
    const ERROR_STATUS = [
        UPLOAD_ERR_OK => 'There is no error, the file uploaded with success',
        UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
    ];

    protected StreamInterface $stream;
    protected ?int $size;
    protected int $error;
    protected ?string $clientFileName;
    protected ?string $clientMediaType;
    protected bool $isMoved = false;

    public function __construct(
        StreamInterface $stream,
        ?int $size,
        int $error,
        ?string $clientFileName,
        ?string $clientMediaType
    ) {
        $this->stream = $stream;
        $this->error = $error;
        $this->size = $size;
        $this->clientFileName = $clientFileName;
        $this->clientMediaType = $clientMediaType;
    }

    public function getStream(): StreamInterface
    {
        if ($this->isMoved) {
            throw new AlreadyMovedFileException();
        }
        return $this->stream;
    }

    public function moveTo($targetPath)
    {
        if (!is_string($targetPath)) {
            throw new InvalidPathTypeException($targetPath);
        }

        if ($this->isMoved) {
            throw new AlreadyMovedFileException();
        }

        $sapiType = php_sapi_name();
        $file = $this->stream->getMetadata('uri');

        if ($sapiType) {
            if (is_uploaded_file($file)) {
                $result = move_uploaded_file($file, $targetPath);
            } else {
                $targetStream = (new StreamFactory)->createStreamFromFile($targetPath, 'w+b');
                while (!$this->stream->eof()) {
                    $targetStream->rewind();
                    $targetStream->write($this->stream->read(1024));
                }
                $targetStream->close();
                $this->stream->close();
                $result = true;
            }
        } else {
            $result = rename($file, $targetPath);
        }

        if (!isset($result)) {
            throw new FileMoveFailException();
        }

        $this->isMoved = true;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getClientFilename(): ?string
    {
        return $this->clientFileName;
    }

    public function getClientMediaType(): ?string
    {
        return $this->clientMediaType;
    }
}
