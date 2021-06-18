<?php

namespace Framework\Http\Client\Response;

use Framework\Http\Client\Request\Exceptions\InvalidUriTypeException;
use Psr\Http\Message\UriInterface;

class RedirectResponse extends Response
{
    public function __construct($uri, int $statusCode = 302)
    {
        if (!is_string($uri) && !$uri instanceof UriInterface) {
            throw new InvalidUriTypeException($uri);
        }

        parent::__construct(null, $statusCode);
        $this->headers['Location'] = [(string)$uri];
    }
}
