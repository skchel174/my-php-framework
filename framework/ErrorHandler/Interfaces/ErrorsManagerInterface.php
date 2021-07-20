<?php

namespace Framework\ErrorHandler\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ErrorsManagerInterface
{
    public function process(\Throwable $e, ServerRequestInterface $request): ResponseInterface;
}
