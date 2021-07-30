<?php

namespace App\Http\Controllers;

use Framework\Http\Client\Response\HtmlResponse;
use Framework\Http\Sessions\Interfaces\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class IndexController
{
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $this->session->set('session_key', 'session_value');
        $html = '<form action="/home" method="POST">
                    <input type="text" name="input">
                    <input type="submit">
                </form>';
        return new HtmlResponse($html);
    }

    public function page(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(
            $request->getParsedBody()['input'] . ' ' . $this->session->get('session_key')
        );
    }
}
