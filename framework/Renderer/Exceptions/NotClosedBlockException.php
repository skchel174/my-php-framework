<?php

namespace Framework\Renderer\Exceptions;

use Framework\Renderer\Block;

class NotClosedBlockException extends \RuntimeException
{
    public function __construct(Block $name)
    {
        $message = 'Block with name "' . $name->getName() . '" is not closed';
        parent::__construct($message, 500);
    }
}
