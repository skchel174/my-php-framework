<?php

namespace Framework\Http\Middlewares\CSRFProtection;

use Framework\Services\CSRFProtection\CSRFToken;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CSRFProtectionStartMiddleware implements MiddlewareInterface
{
    private CSRFToken $token;

    public function __construct(CSRFToken $token)
    {
        $this->token = $token;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->token->setToken();
        $response = $handler->handle($request);
        return $response->withHeader($this->token::HEADER, $this->token->getToken());
    }
}
