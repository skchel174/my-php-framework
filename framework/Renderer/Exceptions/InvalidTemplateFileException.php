<?php

namespace Framework\Renderer\Exceptions;

class InvalidTemplateFileException extends \InvalidArgumentException
{
    public function __construct(string $template)
    {
        $message = 'Template file not exist; passed ' . $template;
        parent::__construct($message, 500);
    }
}
