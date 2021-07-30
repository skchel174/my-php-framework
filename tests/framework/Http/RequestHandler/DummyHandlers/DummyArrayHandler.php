<?php

namespace Tests\framework\Http\RequestHandler\DummyHandlers;

use Framework\Http\Client\Response\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DummyArrayHandler
{
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return new Response();
    }
}
