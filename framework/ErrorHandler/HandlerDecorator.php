<?php

namespace Framework\ErrorHandler;

use Framework\ErrorHandler\Interfaces\HandlerInterface;
use Framework\ErrorHandler\Interfaces\DecoratorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class HandlerDecorator implements DecoratorInterface, HandlerInterface
{
    protected ?HandlerInterface $handler = null;

    public function wrapHandler(HandlerInterface $handler): DecoratorInterface
    {
        $this->handler = $handler;
        return $this;
    }

    public function getHandler(): ?HandlerInterface
    {
        return $this->handler;
    }

    public function log(\Throwable $e): void
    {
        $this->handler->log($e);
    }

    public function render(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        return $this->handler->render($e, $request);
    }

    public function handle(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        $this->log($e);
        return $this->render($e, $request);
    }
}
