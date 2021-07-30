<?php

namespace Tests\framework\Http\RequestHandler\DummyHandlers;

use Framework\Http\Client\Response\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DummyCallableHandler
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return new Response();
    }
}
