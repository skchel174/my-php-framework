<?php

namespace Framework\Http\Client\Stream\Exceptions;

class PositionDeterminateException extends \RuntimeException
{
    public function __construct($code = 0, \Throwable $previous = null)
    {
        parent::__construct('Cannot determine the current position of the pointer', $code, $previous);
    }
}
