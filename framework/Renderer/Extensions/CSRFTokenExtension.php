<?php

namespace Framework\Renderer\Extensions;

use Framework\Services\CSRFProtection\CSRFToken;

class CSRFTokenExtension
{
    private CSRFToken $token;

    public function __construct(CSRFToken $token)
    {
        $this->token = $token;
    }

    public function csrf(): string
    {
        $key = $this->token::KEY;
        $token = $this->token->getToken();
        return '<input type="hidden" name="' . $key . '" value="' . $token . '">';
    }
}
