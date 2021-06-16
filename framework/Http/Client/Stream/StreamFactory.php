<?php

namespace Framework\Http\Client\Stream;

use Framework\Http\Client\Stream\Exceptions\InvalidStreamModeException;
use Framework\Http\Client\Stream\Exceptions\NotOpeningFileException;
use Framework\Http\Client\Stream\Exceptions\ProvidedResourceTypeException;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class StreamFactory implements StreamFactoryInterface
{
    public function createStream(string $content = ''): StreamInterface
    {
        $stream = new Stream(fopen('php://memory', 'w+b'));
        $stream->write($content);
        return $stream;
    }

    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        if (!preg_match('#^[rwaxce]?\+?[tb]?$#', $mode)) {
            throw new InvalidStreamModeException($mode);
        }

        try {
            $resource = fopen($filename, $mode);
        } catch (\Throwable) {
            throw new NotOpeningFileException($filename);
        }

        return new Stream($resource);
    }

    public function createStreamFromResource($resource): StreamInterface
    {
        if (!is_resource($resource)) {
            throw new ProvidedResourceTypeException($resource);
        }
        return new Stream($resource);
    }
}
