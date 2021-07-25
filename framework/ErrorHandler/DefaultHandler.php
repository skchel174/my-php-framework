<?php

namespace Framework\ErrorHandler;

use Framework\ErrorHandler\ErrorFactory\HtmlErrorFactory;
use Framework\ErrorHandler\ErrorFactory\JsonErrorFactory;
use Framework\ErrorHandler\Interfaces\HandlerInterface;
use Framework\Helpers\ResponseTypeHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class DefaultHandler implements HandlerInterface
{
    use ResponseTypeHelper;

    private LoggerInterface $logger;
    private HtmlErrorFactory $htmlErrorFactory;
    private JsonErrorFactory $jsonErrorFactory;

    public function __construct(
        LoggerInterface $logger,
        HtmlErrorFactory $htmlErrorFactory,
        JsonErrorFactory $jsonErrorFactory,
    )
    {
        $this->logger = $logger;
        $this->htmlErrorFactory = $htmlErrorFactory;
        $this->jsonErrorFactory = $jsonErrorFactory;
    }

    public function log(\Exception $e): void
    {
        $this->logger->debug($e->getMessage());
    }

    public function render(\Exception $e, ServerRequestInterface $request): ResponseInterface
    {
        $type = $this->getType($request);
        $errorFactory =  $type === 'json' ? $this->jsonErrorFactory : $this->htmlErrorFactory;
        return $errorFactory->create($e);
    }

    public function handle(\Exception $e, ServerRequestInterface $request): ResponseInterface
    {
        $this->log($e);
        return $this->render($e, $request);
    }
}
