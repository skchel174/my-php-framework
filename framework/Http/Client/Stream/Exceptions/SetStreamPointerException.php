<?php

namespace Framework\Http\Client\Stream\Exceptions;

class SetStreamPointerException extends \RuntimeException
{
    public function __construct($code = 0, \Throwable $previous = null)
    {
        parent::__construct('Unable to set position of the pointer', $code, $previous);
    }
}
