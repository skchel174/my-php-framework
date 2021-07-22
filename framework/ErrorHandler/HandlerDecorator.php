<?php

namespace Framework\ErrorHandler;

use Framework\ErrorHandler\Interfaces\HandlerInterface;
use Framework\ErrorHandler\Interfaces\DecoratorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class HandlerDecorator implements DecoratorInterface, HandlerInterface
{
    protected HandlerInterface $handler;

    public function wrapUp(HandlerInterface $handler): DecoratorInterface
    {
        $this->handler = $handler;
        return $this;
    }

    public function log(\Exception $e): void
    {
        $this->handler->log($e);
    }

    public function render(\Exception $e, ServerRequestInterface $request): ResponseInterface
    {
        return $this->handler->render($e, $request);
    }

    public function handle(\Exception $e, ServerRequestInterface $request): ResponseInterface
    {
        $this->log($e);
        return $this->render($e, $request);
    }
}
