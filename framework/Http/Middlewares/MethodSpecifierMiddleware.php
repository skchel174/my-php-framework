<?php

namespace Framework\Http\Middlewares;

use Framework\Renderer\Extensions\MethodSpecifierExtension;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MethodSpecifierMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestBody = $request->getParsedBody();
        if (is_array($requestBody) && isset($requestBody[MethodSpecifierExtension::KEY])) {
            $request = $request->withMethod($requestBody[MethodSpecifierExtension::KEY]);
        }
        return $handler->handle($request);
    }
}
