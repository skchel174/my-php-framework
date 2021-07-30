<?php

namespace Tests\framework\Http\RequestHandler\DummyHandlers;

use Framework\Http\Client\Response\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DummyPsrHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response();

    }
}
