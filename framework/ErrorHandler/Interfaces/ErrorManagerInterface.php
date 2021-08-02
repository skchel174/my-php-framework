<?php

namespace Framework\ErrorHandler\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ErrorManagerInterface
{
    public function process(\Throwable $e, ServerRequestInterface $request): ResponseInterface;
}
