<?php

namespace Framework\Http\Client\Request;

use Framework\Http\Client\Request\Exceptions\InvalidUriTypeException;
use Framework\Http\Client\Uri\UriFactory;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class RequestFactory implements RequestFactoryInterface
{
    public function createRequest(string $method, $uri): RequestInterface
    {
        if (!is_string($uri) && (is_object($uri) && !$uri instanceof UriInterface)) {
            throw new InvalidUriTypeException($uri);
        }
        $uri = is_string($uri) ? (new UriFactory)->createUri($uri) : $uri;
        return new Request($method, $uri);
    }
}
