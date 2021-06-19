<?php

namespace Framework\Http\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MiddlewareWrapper
{
    const ROUTE = '_route_name';

    protected mixed $middleware;
    protected ?string $route = null;

    public function __construct(mixed $middleware)
    {
        $this->middleware = $middleware;
    }

    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        return $this->middleware->process($request, new RequestHandlerWrapper($next));
    }

    public function route(string $route): static
    {
        $this->route = $route;
        return $this;
    }

    public function isAdmitted(ServerRequestInterface $request): bool
    {
        if ($this->route === null) {
            return true;
        }
        return $this->route === $request->getAttribute(static::ROUTE);
    }
}
