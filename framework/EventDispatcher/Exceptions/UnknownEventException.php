<?php

namespace Framework\EventDispatcher\Exceptions;

class UnknownEventException extends \InvalidArgumentException
{
    public function __construct(string $event)
    {
        $message = 'Trying to dispatch unknown event; called: ' . $event;
        parent::__construct($message);
    }
}
