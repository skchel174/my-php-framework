<?php

namespace Framework\Http\Middlewares\CSRFProtection;

use Framework\Services\CSRFProtection\CSRFTokenBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CSRFProtectionStartMiddleware implements MiddlewareInterface
{
    private CSRFTokenBuilder $tokenBuilder;

    public function __construct(CSRFTokenBuilder $tokenBuilder)
    {
        $this->tokenBuilder = $tokenBuilder;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->tokenBuilder->setToken();
        return $handler->handle($request);
    }
}
