<?php

namespace Tests\framework\Http\Middlewares\RequestHandler;

use Framework\Http\Client\Request\ServerRequest;
use Framework\Http\Middlewares\RequestHandler\RequestHandler;
use Framework\Http\Middlewares\RequestHandler\RequestHandlerResolver;
use Framework\Http\Router\Route;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
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
        $handler = new RequestHandler(new RequestHandlerResolver());
        $response = $handler->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function handlersProvider(): array
    {
        return [
            'Class name handler' => [new Route('/', DummyPsrHandler::class, ['GET'])],
            'Callable handler' => [new Route('/', DummyCallableHandler::class, ['GET'])],
            'Array handler' => [new Route('/', [DummyArrayHandler::class, 'index'], ['GET'])],
        ];
    }
}
