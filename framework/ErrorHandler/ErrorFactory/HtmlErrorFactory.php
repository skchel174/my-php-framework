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
    private array $templates;

    public function __construct(RendererInterface $renderer, array $templates)
    {
        $this->renderer = $renderer;
        $this->templates = $templates;
    }

    public function create(\Exception $e): ResponseInterface
    {
        $code = $this->normalizeCode($e->getCode());
        $template = $this->templates[$code] ?? $this->templates['default'];
        $html = $this->renderer->render($template, [
            'code' => $code,
            'message' => $e->getMessage(),
        ]);

        return new HtmlResponse($html, $code);
    }
}