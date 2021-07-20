<?php

namespace Framework\ErrorHandler;

use Framework\ErrorHandler\Interfaces\HandlerInterface;
use Framework\ErrorHandler\Interfaces\WrapperInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class HandlerWrapper implements WrapperInterface, HandlerInterface
{
    protected HandlerInterface $handler;

    public function wrapUp(HandlerInterface $handler): WrapperInterface
    {
        $this->handler = $handler;
        return $this;
    }

    public function handle(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        $this->log($e);
        return $this->display($e);
    }

    protected function log(\Throwable $e): void
    {
        $this->handler->log($e);
    }

    protected function display(\Throwable $e): ResponseInterface
    {
        return  $this->handler->display($e);
    }
}
