<?php

namespace Framework\Renderer\Exceptions;

class UnknownFilterRegisterException extends \InvalidArgumentException
{
    public function __construct(string $filter)
    {
        $message = 'Function with name "' . $filter . '" not exist';
        parent::__construct($message, 500);
    }
}
