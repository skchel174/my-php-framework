<?php

namespace Framework\ErrorHandler\Middleware;

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
            throw new \Exception('test exception');
            return $handler->handle($request);
        } catch (\Exception $e) {
            return $this->errorsManager->process($e, $request);
        }
    }
}