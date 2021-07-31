<?php

namespace Framework\Renderer\Extensions;

class MethodSpecifierExtension
{
    const KEY = '_http_method';

    public function method(string $method): string
    {
        $key = static::KEY;
        $method = \strtoupper($method);
        return '<input type="hidden" name="' . $key . '" value="' . $method . '">';
    }
}
