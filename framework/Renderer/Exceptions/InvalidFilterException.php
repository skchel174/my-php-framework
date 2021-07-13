<?php

namespace Framework\Renderer\Exceptions;

class InvalidFilterException extends \InvalidArgumentException
{
    public function __construct(string $filter)
    {
        $message = 'Filter with name "' . $filter . '" not registered';
        parent::__construct($message, 500);
    }
}
