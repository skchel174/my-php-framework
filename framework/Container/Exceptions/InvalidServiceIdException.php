<?php

namespace Framework\Container\Exceptions;

class InvalidServiceIdException extends \InvalidArgumentException
{
    public function __construct(string $id)
    {
        $message = 'Service id must include alphabetic characters; passed ' . $id;
        parent::__construct($message, 500);
    }
}
