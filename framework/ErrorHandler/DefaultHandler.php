<?php

namespace Framework\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class DefaultHandler implements Interfaces\HandlerInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger, )
    {
        $this->logger = $logger;

    }

    public function handle(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        $this->log($e);
        return $this->display($e);
    }

    protected function log(\Throwable $e): void
    {
        $this->logger->debug($e->getMessage());
    }

    protected function display(\Throwable $e): ResponseInterface
    {
        return new Response();
    }
}