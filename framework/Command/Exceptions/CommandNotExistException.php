<?php

namespace Framework\Command\Exceptions;

class CommandNotExistException extends \RuntimeException
{
    public function __construct()
    {
        $message = 'Unknown command called. Use "help" to display a list of exists commands';
        parent::__construct($message);
    }
}
