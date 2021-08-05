<?php

namespace Framework\EventDispatcher\Exceptions;


class InvalidEventTypeException extends \InvalidArgumentException
{
    public function __construct(string $event)
    {
        $message = 'Event object must implement the EventInterface; passed ' . $event;
        parent::__construct($message);
    }
}
