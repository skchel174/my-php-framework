<?php

namespace Framework\ErrorHandler\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface HandlerInterface
{
    public function log(\Exception $e);
    public function render(\Exception $e, ServerRequestInterface $request): ResponseInterface;
    public function handle(\Exception $e, ServerRequestInterface $request): ResponseInterface;
}
