<?php

namespace Framework\Services\CSRFProtection\Exceptions;

class FailedValidationCSRFTokenException extends \RuntimeException
{
    public function __construct()
    {
        $message = 'CSRF token is missing or does not match client`s session token';
        parent::__construct($message, 419);
    }
}
