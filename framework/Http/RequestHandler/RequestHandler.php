<?php

namespace Framework\Http\RequestHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{
    protected RequestHandlerResolver $resolver;

    public function __construct(RequestHandlerResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $handler = $this->resolver->resolve(
            $request->getAttribute(RequestHandlerInterface::class)
        );
        return $handler($request);
    }
}
