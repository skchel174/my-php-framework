<?php

namespace Framework\ErrorHandler;

use Framework\ErrorHandler\Interfaces\ErrorGeneratorInterface;
use Framework\ErrorHandler\Interfaces\ErrorHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class ErrorHandler implements ErrorHandlerInterface
{
    private ErrorGeneratorInterface $errorGenerator;
    private LoggerInterface $logger;

    public function __construct(ErrorGeneratorInterface $errorGenerator, LoggerInterface $logger)
    {
        $this->errorGenerator = $errorGenerator;
        $this->logger = $logger;
    }

    public function handle(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        $this->log($e);
        return $this->errorGenerator->generate($e, $request);
    }

    protected function log(\Throwable $e): void
    {
        $this->logger->debug(get_class($e), [
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
    }
}