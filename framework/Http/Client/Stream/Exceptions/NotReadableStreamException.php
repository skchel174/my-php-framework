<?php

namespace Framework\Http\Client\Stream\Exceptions;

class NotReadableStreamException extends \RuntimeException
{
    public function __construct($code = 0, \Throwable $previous = null)
    {
        parent::__construct('Stream is not readable', $code, $previous);
    }
}
