<?php

namespace Framework\Renderer\Exceptions;

class ExtensionNotExistException extends \InvalidArgumentException
{
    public function __construct(string $method)
    {
        $message = 'Extension with method "' . $method . '" not register';
        parent::__construct($message, 500);
    }
}
