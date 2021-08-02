<?php

namespace App\Http\Controllers;

use Framework\DB\DB;
use Framework\Http\Client\Response\HtmlResponse;
use Framework\Http\Sessions\Interfaces\SessionInterface;
use Framework\Renderer\Interfaces\RendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class IndexController
{
    private SessionInterface $session;
    private RendererInterface $renderer;
    private DB $mysql;
    private DB $sqlite;

    public function __construct(
        SessionInterface $session,
        RendererInterface $renderer,
        DB $mysql,
        DB $sqlite,
    )
    {
        $this->session = $session;
        $this->renderer = $renderer;
        $this->mysql = $mysql;
        $this->sqlite = $sqlite;
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        var_dump($this->mysql);
        var_dump($this->sqlite);
        $this->session->set('session_key', 'session_value');
        $html = $this->renderer->render('index.phtml');
        return new HtmlResponse($html);
    }

    public function page(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(
            $request->getMethod() . ' ' .
            $request->getParsedBody()['input'] . ' ' .
            $this->session->get('session_key')
        );
    }
}
