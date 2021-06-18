<?php

namespace Framework\Http\Client\Stream\Exceptions;

class ErrorReadingStreamException extends \RuntimeException
{
    public function __construct($code = 0, \Throwable $previous = null)
    {
        parent::__construct('Error reading stream data', $code, $previous);
    }
}
