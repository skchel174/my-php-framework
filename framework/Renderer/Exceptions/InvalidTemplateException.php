<?php

namespace Framework\Renderer\Exceptions;

class InvalidTemplateException extends \InvalidArgumentException
{
    public function __construct($template)
    {
        $message = 'Attempting to render a not exist template; pass ' . $template;
        parent::__construct($message, 500);
    }
}
