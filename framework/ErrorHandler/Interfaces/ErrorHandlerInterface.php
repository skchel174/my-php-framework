<?php

namespace Framework\ErrorHandler\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ErrorHandlerInterface
{
    public function handle(\Throwable $e, ServerRequestInterface $request): ResponseInterface;
}
