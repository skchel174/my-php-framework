<?php

namespace Framework\Renderer\Exceptions;

class NotOpenedBlockException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Attempt to close an unopened block', 500);
    }
}
