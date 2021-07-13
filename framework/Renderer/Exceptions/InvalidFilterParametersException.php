<?php

namespace Framework\Renderer\Exceptions;

class InvalidFilterParametersException extends \InvalidArgumentException
{
    public function __construct(string $filter)
    {
        $message = 'Function with name "' . $filter . '" must have only one required parameter';
        parent::__construct($message, 500);
    }
}
