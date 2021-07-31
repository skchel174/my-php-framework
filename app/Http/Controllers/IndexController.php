<?php

namespace App\Http\Controllers;

use Framework\Http\Client\Response\HtmlResponse;
use Framework\Http\Sessions\Interfaces\SessionInterface;
use Framework\Renderer\Interfaces\RendererInterface;
use Framework\Renderer\View;
use Memcached;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class IndexController
{
    private SessionInterface $session;
    private RendererInterface $renderer;

    public function __construct(SessionInterface $session, RendererInterface $renderer)
    {
        $this->session = $session;
        $this->renderer = $renderer;
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $this->session->set('session_key', 'session_value');
        $html = $this->renderer->render('index.phtml');
        return new HtmlResponse($html);
    }

    public function page(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(
            $request->getParsedBody()['input'] . ' ' . $this->session->get('session_key')
        );
    }
}
