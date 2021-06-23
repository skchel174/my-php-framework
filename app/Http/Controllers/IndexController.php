<?php

namespace App\Http\Controllers;

use Framework\Http\Client\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class IndexController
{
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $html = '<form action="/home" method="POST">
                    <input type="text" name="input">
                    <input type="submit">
                </form>';
        return new HtmlResponse($html);
    }

    public function page(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($request->getParsedBody()['input']);
    }
}
