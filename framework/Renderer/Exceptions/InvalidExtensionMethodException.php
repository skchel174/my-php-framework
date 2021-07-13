<?php

namespace Framework\Renderer\Exceptions;

class InvalidExtensionMethodException extends \InvalidArgumentException
{
    public function __construct(object $extension, string $method)
    {
        $message = 'Extension "' . $extension::class . '" has no method "' . $method . '"';
        parent::__construct($message, 500);
    }
}
