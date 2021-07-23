<?php

namespace Framework\ErrorHandler;

use Framework\ErrorHandler\Interfaces\ErrorsManagerInterface;
use Framework\ErrorHandler\Interfaces\HandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ErrorManager implements ErrorsManagerInterface
{
    private HandlersCollection $handlers;

    public function __construct(HandlersCollection $handlers)
    {
        $this->handlers= $handlers;
    }

    public function process(\Exception $e, ServerRequestInterface $request): ResponseInterface
    {
        $handler = $this->buildSequence($e::class);
        return $handler->handle($e, $request);
    }

    protected function buildSequence(string $exception): HandlerInterface
    {
        $handler = $this->handlers->get(\Exception::class);

        while ($exception) {
            $reflection = new \ReflectionClass($exception);
            $exception = $reflection->getParentClass();

            if ($exception && $this->handlers->has($exception)) {
                $wrapper = $this->handlers->get($exception);
                $handler = $wrapper->wrapUp($handler);
            }
        };

        return $handler;
    }
}
