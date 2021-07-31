<?php

namespace Framework\Http\Sessions\Exceptions;

class SessionNotStartedException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Session not started');
    }
}
