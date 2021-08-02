<?php

namespace Framework\ErrorHandler\Interfaces;

use Psr\Http\Message\ServerRequestInterface;

interface DebuggerInterface
{
    public function handle(\Throwable $e, ServerRequestInterface $request): void;
}
