<?php

namespace Framework\Container\Exceptions;

use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;

class InvalidServiceIdException extends InvalidArgumentException implements ContainerExceptionInterface
{
    public function __construct(string $id)
    {
        $message = 'Service id must include alphabetic characters; passed ' . $id;
        parent::__construct($message, 500);
    }
}
