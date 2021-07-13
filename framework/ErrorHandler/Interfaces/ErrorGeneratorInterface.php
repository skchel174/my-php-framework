<?php

namespace Framework\ErrorHandler\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ErrorGeneratorInterface
{
    public function generate(\Throwable $e, ServerRequestInterface $request): ResponseInterface;
}
