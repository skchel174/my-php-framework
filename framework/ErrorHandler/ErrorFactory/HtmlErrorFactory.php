<?php

namespace Framework\ErrorHandler\ErrorFactory;

use Framework\Http\Client\Response\HtmlResponse;
use Framework\Renderer\Interfaces\RendererInterface;
use Psr\Http\Message\ResponseInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class HtmlErrorFactory extends ErrorFactory
{
    private RendererInterface $renderer;
    private Run $whoops;
    private array $config;

    public function __construct(RendererInterface $renderer, Run $whoops, array $config)
    {
        $this->renderer = $renderer;
        $this->whoops = $whoops;
        $this->config = $config;
    }

    public function create(\Exception $e): ResponseInterface
    {
        $code = $this->normalizeCode($e->getCode());

        if ($this->config['debug']) {
            $this->whoops->allowQuit(false);
            $this->whoops->writeToOutput(false);
            $this->whoops->pushHandler(new PrettyPageHandler);
            $html = $this->whoops->handleException($e);
        } else {
            $template = $this->config['error']['templates'][$code]
                ?? $this->config['error']['templates']['default'];
            $html = $this->renderer->render($template);
        }

        return new HtmlResponse($html, $code);
    }
}