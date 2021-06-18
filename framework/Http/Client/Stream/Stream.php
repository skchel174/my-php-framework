<?php

namespace Framework\Http\Client\Stream;

use Framework\Http\Client\Stream\Exceptions\ErrorReadingStreamException;
use Framework\Http\Client\Stream\Exceptions\HandleNotResourceException;
use Framework\Http\Client\Stream\Exceptions\NotReadableStreamException;
use Framework\Http\Client\Stream\Exceptions\PositionDeterminateException;
use Framework\Http\Client\Stream\Exceptions\ProvidedResourceTypeException;
use Framework\Http\Client\Stream\Exceptions\SetStreamPointerException;
use Framework\Http\Client\Stream\Exceptions\WritingToStreamException;
use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    /** @var null|resource */
    protected $resource;

    public function __construct($resource = null)
    {
        if ($resource) {
            $this->attach($resource);
        }
    }

    public function __toString(): string
    {
        if (!$this->isReadable()) {
            return '';
        }

        try {
            return $this->getContents();
        } catch (\Throwable) {
            return '';
        }
    }

    public function close()
    {
        fclose($this->resource);
    }

    public function detach()
    {
        $this->close();
        $this->resource = null;
    }

    public function attach($resource)
    {
        if (!is_resource($resource)) {
            throw new ProvidedResourceTypeException($resource);
        }
        $this->resource = $resource;
    }

    public function getSize()
    {
        if (!is_resource($this->resource)) {
            return null;
        }
        $stat = fstat($this->resource);
        return is_array($stat) ? $stat['size'] : null;
    }

    public function tell(): int
    {
        if (!is_resource($this->resource)) {
            throw new HandleNotResourceException();
        }

        $position = ftell($this->resource);

        if (!is_integer($position)) {
            throw new PositionDeterminateException();
        }

        return $position;
    }

    public function eof(): bool
    {
        if (!is_resource($this->resource)) {
            return true;
        }
        return feof($this->resource);
    }

    public function isSeekable()
    {
        if (!is_resource($this->resource)) {
            return false;
        }
        return $this->getMetadata('seekable');
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        if (!is_resource($this->resource)) {
            throw new HandleNotResourceException();
        }

        if (!$this->isSeekable()) {
            throw new SetStreamPointerException();
        }

        fseek($this->resource, $offset, $whence);
    }

    public function rewind()
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        if (!is_resource($this->resource)) {
            return false;
        }
        $mode = $this->getMetadata('mode');
        return str_contains($mode, 'w') || str_contains($mode, '+');
    }

    public function write($string): int
    {
        if (!$this->isWritable()) {
            throw new WritingToStreamException();
        }

        $result = fwrite($this->resource, $string);

        if ($result === false) {
            throw new WritingToStreamException();
        }

        return $result;
    }

    public function isReadable(): bool
    {
        if (!is_resource($this->resource)) {
            return false;
        }

        $mode = $this->getMetadata('mode');
        return str_contains($mode, 'r') || str_contains($mode, '+');
    }

    public function read($length): string
    {
        if (!$this->isReadable()) {
            throw new NotReadableStreamException();
        }

        $result = fread($this->resource, $length);

        if ($result === false) {
            throw new ErrorReadingStreamException();
        }

        return $result;
    }

    public function getContents(): string
    {
        if (!$this->isReadable()) {
            throw new NotReadableStreamException();
        }

        $this->rewind();
        $result = stream_get_contents($this->resource);

        if ($result === false) {
            throw new ErrorReadingStreamException();
        }

        return $result;
    }

    public function getMetadata($key = null)
    {
        $meta = stream_get_meta_data($this->resource);

        if (is_null($key)) {
            return $meta;
        }

        return $meta[$key] ?? null;
    }
}
