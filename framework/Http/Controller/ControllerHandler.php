<?php

namespace Framework\Http\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ControllerHandler implements RequestHandlerInterface
{
    const HANDLER = '_controller_handler';

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var ResponseInterface $response */
        [$handler, $method] = $request->getAttribute(static::HANDLER);
        $controller = new $handler();
        return $controller->$method($request);
    }
}
