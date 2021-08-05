<?php

namespace Framework\EventDispatcher\Exceptions;

class InvalidEventListenerTypeException extends \InvalidArgumentException
{
    public function __construct(mixed $listener)
    {
        $message = 'EventListener object must implement the EventListenerInterface; passed ' . $listener;
        parent::__construct($message);
    }
}
