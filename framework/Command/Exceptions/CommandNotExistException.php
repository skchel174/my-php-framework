<?php

namespace Framework\Command\Exceptions;

class CommandNotExistException extends \RuntimeException
{
    public function __construct(string $command)
    {
        $message = 'Unknown command called: "' . $command . '". Use "help" to display a list of exists commands';
        parent::__construct($message);
    }
}
