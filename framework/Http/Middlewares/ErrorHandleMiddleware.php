<?php

namespace Framework\Http\Middlewares;

use Framework\ErrorHandler\Interfaces\ErrorManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandleMiddleware implements MiddlewareInterface
{
    private ErrorManagerInterface $errorsManager;

    public function __construct(ErrorManagerInterface $errorsManager)
    {
        $this->errorsManager = $errorsManager;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Throwable $e) {
            return $this->errorsManager->process($e, $request);
        }
    }
}
