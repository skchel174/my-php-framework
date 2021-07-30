<?php

namespace Framework\Services\CSRFProtection\Exceptions;

class MissingCSRFTokenException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('An attempt to get a token which missing in a session');
    }
}
