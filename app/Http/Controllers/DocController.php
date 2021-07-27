<?php

namespace App\Http\Controllers;

use Framework\Http\Client\Response\HtmlResponse;
use Framework\Renderer\Interfaces\RendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DocController
{
    private RendererInterface $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $html = $this->renderer->render('doc/main.phtml');
        return new HtmlResponse($html);
    }
}
