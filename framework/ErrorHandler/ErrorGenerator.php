<?php

namespace Framework\ErrorHandler;

use Framework\ErrorHandler\Interfaces\ErrorGeneratorInterface;
use Framework\Http\Client\Response\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ErrorGenerator implements ErrorGeneratorInterface
{
    private array $config;
    private HtmlErrorFactory $htmlErrorFactory;
    private JsonErrorFactory $jsonErrorFactory;

    public function __construct(array $config, HtmlErrorFactory $htmlErrorFactory, JsonErrorFactory $jsonErrorFactory)
    {
        $this->config = $config;
        $this->htmlErrorFactory = $htmlErrorFactory;
        $this->jsonErrorFactory = $jsonErrorFactory;
    }

    public function generate(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        $acceptType = $request->getHeaderLine('Accept');
        $errorFactory = str_contains($acceptType, 'json') ? $this->jsonErrorFactory : $this->htmlErrorFactory;
        return $errorFactory->create($e, $this->config);
    }

    protected function errorCode(int $code): int
    {
        if (!array_key_exists($code, Response::REASON_PHRASES)) {
            $code = 500;
        }
        return $code;
    }
}