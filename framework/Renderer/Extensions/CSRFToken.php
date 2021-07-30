<?php

namespace Framework\Renderer\Extensions;

use Framework\Services\CSRFProtection\CSRFTokenBuilder;

class CSRFToken
{
    private CSRFTokenBuilder $tokenBuilder;

    public function __construct(CSRFTokenBuilder $tokenBuilder)
    {
        $this->tokenBuilder = $tokenBuilder;
    }

    public function csrf(): string
    {
        $key = $this->tokenBuilder::KEY;
        $token = $this->tokenBuilder->getToken();
        return '<input type="hidden" name="' . $key . '" value="' . $token . '">';
    }
}
