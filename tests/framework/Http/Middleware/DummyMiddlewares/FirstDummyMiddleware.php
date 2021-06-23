<?php

namespace Tests\framework\Http\Middleware\DummyMiddlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FirstDummyMiddleware
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute(self::class, true);
        $response = $handler->handle($request);
        return $response->withHeader(self::class, true);
    }
}
