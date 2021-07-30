<?php

namespace Tests\framework\Http\RequestHandler;

use Framework\Container\Container;
use Framework\Http\Client\Request\ServerRequest;
use Framework\Http\Client\Response\Response;
use Framework\Http\RequestHandler\RequestHandler;
use Framework\Http\RequestHandler\RequestHandlerResolver;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tests\framework\Http\RequestHandler\DummyHandlers\DummyArrayHandler;
use Tests\framework\Http\RequestHandler\DummyHandlers\DummyCallableHandler;
use Tests\framework\Http\RequestHandler\DummyHandlers\DummyPsrHandler;

class RequestHandlerTest extends TestCase
{
    /**
     * @dataProvider handlersProvider
     */
    public function testHandle(mixed $handler): void
    {
        $request = (new ServerRequest)->withAttribute(RequestHandlerInterface::class, $handler);
        $resolver = new RequestHandlerResolver(Container::getInstance());
        $handler = new RequestHandler($resolver);
        $response = $handler->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function handlersProvider(): array
    {
        return [
            'Closure handler' => [function (ServerRequestInterface $request): ResponseInterface {
                return new Response();
            }],
            'Class name handler' => [DummyPsrHandler::class],
            'Callable handler' => [DummyCallableHandler::class],
            'Array handler' => [[DummyArrayHandler::class, 'index']],
        ];
    }
}
