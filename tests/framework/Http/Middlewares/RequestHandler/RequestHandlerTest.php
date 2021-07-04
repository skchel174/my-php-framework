<?php

namespace Tests\framework\Http\Middlewares\RequestHandler;

use Framework\Container\Container;
use Framework\Http\Client\Request\ServerRequest;
use Framework\Http\Client\Response\Response;
use Framework\Http\Middlewares\RequestHandler\RequestHandler;
use Framework\Http\Middlewares\RequestHandler\RequestHandlerResolver;
use Framework\Http\Router\Route;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tests\framework\Http\Middlewares\DummyHandlers\DummyArrayHandler;
use Tests\framework\Http\Middlewares\DummyHandlers\DummyCallableHandler;
use Tests\framework\Http\Middlewares\DummyHandlers\DummyPsrHandler;

class RequestHandlerTest extends TestCase
{
    /**
     * @dataProvider handlersProvider
     */
    public function testHandle(Route $handler): void
    {
        $request = (new ServerRequest)->withAttribute(Route::class, $handler);
        $resolver = new RequestHandlerResolver(new Container());
        $handler = new RequestHandler($resolver);
        $response = $handler->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function handlersProvider(): array
    {
        return [
            'Closure handler' => [new Route('/', function (ServerRequestInterface $request): ResponseInterface {
                return new Response();
            }, ['GET'])],
            'Class name handler' => [new Route('/', DummyPsrHandler::class, ['GET'])],
            'Callable handler' => [new Route('/', DummyCallableHandler::class, ['GET'])],
            'Array handler' => [new Route('/', [DummyArrayHandler::class, 'index'], ['GET'])],
        ];
    }
}
