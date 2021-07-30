<?php

namespace Framework\Http\MiddlewareDispatcher;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandlerWrapper implements RequestHandlerInterface
{
    protected $next;

    public function __construct(callable $next)
    {
        $this->next = $next;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return ($this->next)($request);
    }
}