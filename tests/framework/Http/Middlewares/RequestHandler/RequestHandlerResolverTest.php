<?php

namespace Tests\framework\Http\Middlewares\RequestHandler;

use Framework\Container\Container;
use Framework\Http\Client\Response\Response;
use Framework\Http\Middlewares\RequestHandler\Exceptions\InvalidRequestHandlerTypeException;
use Framework\Http\Middlewares\RequestHandler\RequestHandlerResolver;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tests\framework\Http\Middlewares\DummyHandlers\DummyArrayHandler;
use Tests\framework\Http\Middlewares\DummyHandlers\DummyCallableHandler;
use Tests\framework\Http\Middlewares\DummyHandlers\DummyPsrHandler;

class RequestHandlerResolverTest extends TestCase
{
    /**
     * @dataProvider handlersProvider
     */
    public function testResolve(mixed $handler): void
    {
        $resolver = new RequestHandlerResolver(new Container());
        $result = $resolver->resolve($handler);

        $this->assertIsCallable($result);
    }

    /**
     * @dataProvider invalidHandlersProvider
     */
    public function testInvalidHandlersResolve(mixed $handler): void
    {
        $this->expectException(InvalidRequestHandlerTypeException::class);

        $resolver = new RequestHandlerResolver(new Container());
        $resolver->resolve($handler);
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

    public function invalidHandlersProvider(): array
    {
        return [
            'Null handler' => [null],
            'Invalid string handler' => [\stdClass::class],
            'Invalid object handler' => [new \stdClass],
            'Invalid array handler' => [[\stdClass::class]],
        ];
    }
}
