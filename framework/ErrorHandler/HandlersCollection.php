<?php

namespace Framework\ErrorHandler;

use Framework\ErrorHandler\Interfaces\HandlerInterface;

class HandlersCollection
{
    private array $handlers = [];

    public function register(string $exception, string|HandlerInterface $handler): void
    {
        $this->handlers[$exception] = $handler;
    }

    public function get(string $exception): null|string|HandlerInterface
    {
        return $this->handlers[$exception] ?? null;
    }

    public function has(string $exception): bool
    {
        return array_key_exists($exception, $this->handlers);
    }
}