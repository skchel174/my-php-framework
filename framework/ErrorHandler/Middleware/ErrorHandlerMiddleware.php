<?php

namespace Framework\ErrorHandler\Middleware;

use Framework\ErrorHandler\Interfaces\ErrorsManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    private ErrorsManagerInterface $errorsManager;

    public function __construct(ErrorsManagerInterface $errorsManager)
    {
        $this->errorsManager = $errorsManager;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $handler->handle($request);
        } catch (\Exception $e) {
            $this->errorsManager->process($e, $request);
        }
    }
}