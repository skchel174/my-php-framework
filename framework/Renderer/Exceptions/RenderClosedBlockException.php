<?php

namespace Framework\Renderer\Exceptions;

class RenderClosedBlockException extends \RuntimeException
{
    public function __construct(string $block)
    {
        $message = 'Trying render closed block with name "' . $block . '"';
        parent::__construct($message, 500);
    }
}
