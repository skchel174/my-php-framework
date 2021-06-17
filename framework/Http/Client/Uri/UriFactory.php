<?php

namespace Framework\Http\Client\Uri;

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class UriFactory implements UriFactoryInterface
{
    public function createUri(string $uri = ''): UriInterface
    {
        if ($uri) {
            return new Uri(...parse_url($uri));
        }
        return new Uri();
    }

    public function createUriFromSapi(array $server = []): UriInterface
    {
        $server = $server ?: $_SERVER;
        return new Uri(
            strtolower(explode('/', $server['SERVER_PROTOCOL'])[0]) ?? '',
            $server['PHP_AUTH_USER'] ?? '',
            $server['PHP_AUTH_PW'] ?? null,
            parse_url($server['HTTP_HOST'], PHP_URL_HOST) ?? '',
            $server['SERVER_PORT'] ?? null,
            parse_url($server['REQUEST_URI'], PHP_URL_PATH) ?? '',
            parse_url($server['REQUEST_URI'], PHP_URL_QUERY) ?? '',
        );
    }
}
