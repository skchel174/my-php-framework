<?php

namespace Framework\Http\Client\Response;

use Framework\Http\Client\Message\Exceptions\InvalidBodyTypeException;

class HtmlResponse extends Response
{
    public function __construct($body, int $statusCode = 200, string $reasonPhrase = '')
    {
        if (!is_string($body)) {
            throw new InvalidBodyTypeException($body);
        }

        parent::__construct($body, $statusCode, $reasonPhrase);
        $this->headers['Content-Type'] = ['text/html; charset=utf-8'];
    }
}
