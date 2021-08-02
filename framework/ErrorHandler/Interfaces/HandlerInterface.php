<?php

namespace Framework\ErrorHandler\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface HandlerInterface
{
    public function log(\Throwable $e);
    public function render(\Throwable $e, ServerRequestInterface $request): ResponseInterface;
    public function handle(\Throwable $e, ServerRequestInterface $request): ResponseInterface;
}
