<?php

namespace Framework\Http\Client\Stream\Exceptions;

class WritingToStreamException extends \RuntimeException
{
    public function __construct($code = 0, \Throwable $previous = null)
    {
        parent::__construct('Error writing to stream', $code, $previous);
    }
}
