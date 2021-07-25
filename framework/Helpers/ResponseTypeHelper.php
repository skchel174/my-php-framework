<?php

namespace Framework\Helpers;

use Psr\Http\Message\ServerRequestInterface;

trait ResponseTypeHelper
{
    public function getResponseType(ServerRequestInterface $request): string
    {
        $header = $request->getHeaderLine('Accept') ?? $request->getHeaderLine('Content-Type');

        if (str_contains($header, 'json')) {
            return 'json';
        }

        if (str_contains($header, 'xml')) {
            return 'xml';
        }

        return 'html';
    }
}
