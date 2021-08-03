<?php

namespace Framework\ErrorHandler;

use Framework\ErrorHandler\Interfaces\DebuggerInterface;
use Framework\ErrorHandler\Interfaces\ErrorManagerInterface;
use Framework\ErrorHandler\Interfaces\HandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ErrorManager implements ErrorManagerInterface
{
    private HandlersCollection $handlers;
    private DebuggerInterface $debugger;
    private bool $debug;

    public function __construct(
        HandlersCollection $handlers,
        DebuggerInterface $debugger,
        bool $debug,
    )
    {
        $this->handlers = $handlers;
        $this->debugger = $debugger;
        $this->debug = $debug;
    }

    public static function registerErrorHandler(): void
    {
        set_error_handler(function (int $number, string $message, string $file = null, int $line = null) {
            throw new \ErrorException($message, 0, $number, $file, $line);
        });
    }

    public function process(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        if ($this->debug) {
            $this->debugger->handle($e, $request);
        }

        $handler = $this->buildSequence($e::class);
        return $handler->handle($e, $request);
    }

    protected function buildSequence(string $exception): HandlerInterface
    {
        $stack = new \SplStack();

        do {
            if ($this->handlers->has($exception)) {
                $stack->push($this->handlers->get($exception));
            }
        } while ($exception = $this->getExceptionParent($exception));

        $handler = $this->handlers->get(\Throwable::class);

        while (!$stack->isEmpty()) {
            /** @var HandlerDecorator $wrapper */
            $wrapper = $stack->pop();
            $wrapper->wrapHandler($handler);
            $handler = $wrapper;
        }

        return $handler;
    }

    protected function getExceptionParent(string $exception): ?string
    {
        $reflection = new \ReflectionClass($exception);
        $parent = $reflection->getParentClass();
        return $parent ? $parent->getName() : null;
    }
}
