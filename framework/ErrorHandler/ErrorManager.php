<?php

namespace Framework\ErrorHandler;

use Framework\ErrorHandler\Interfaces\ErrorManagerInterface;
use Framework\ErrorHandler\Interfaces\HandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ErrorManager implements ErrorManagerInterface
{
    private HandlersCollection $handlers;

    public function __construct(HandlersCollection $handlers)
    {
//        ini_set('display_errors', 0);
//        set_error_handler([$this, 'translateErrorToException']);
//        register_shutdown_function(function () {
//            if ($error = error_get_last()) {
//                echo new \ErrorException(
//                    $error['message'],
//                    0, $error['type'],
//                    $error['file'],
//                    $error['line']);
//            };
//        });

        $this->handlers = $handlers;
    }

    public function process(\Exception $e, ServerRequestInterface $request): ResponseInterface
    {

        $handler = $this->buildSequence($e::class);
        return $handler->handle($e, $request);
    }

    protected function buildSequence(string $exception): HandlerInterface
    {
        $stack = new \SplStack();

        do {
            if ($exception !== \Exception::class && $this->handlers->has($exception)) {
                $stack->push($this->handlers->get($exception));
            }
        } while ($exception = $this->getExceptionParent($exception));

        $handler = $this->handlers->get(\Exception::class);

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

    public function translateErrorToException(int $number, string $message, string $file = null, int $line = null): bool
    {
        throw new \ErrorException($message, 0, $number, $file, $line);
    }
}
