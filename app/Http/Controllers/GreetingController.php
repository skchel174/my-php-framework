<?php

namespace App\Http\Controllers;

use Framework\Http\Client\Response\HtmlResponse;
use Framework\Renderer\Interfaces\RendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GreetingController
{
    private RendererInterface $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $html = $this->renderer->render('greeting/main.phtml');
        return new HtmlResponse($html);
    }

    public function doc(ServerRequestInterface $request): ResponseInterface
    {

    }
}
