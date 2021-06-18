<?php

namespace Framework\Http\Client\Stream\Exceptions;

class HandleNotResourceException extends \RuntimeException
{
    public function __construct($code = 0, \Throwable $previous = null)
    {
        parent::__construct('Error when trying to process a non-resource value', $code, $previous);
    }
}
