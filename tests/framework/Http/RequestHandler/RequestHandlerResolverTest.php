<?php

namespace Tests\framework\Http\RequestHandler;

use Framework\Container\Container;
use Framework\Http\Client\Response\Response;
use Framework\Http\RequestHandler\Exceptions\InvalidRequestHandlerTypeException;
use Framework\Http\RequestHandler\RequestHandlerResolver;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tests\framework\Http\RequestHandler\DummyHandlers\DummyArrayHandler;
use Tests\framework\Http\RequestHandler\DummyHandlers\DummyCallableHandler;
use Tests\framework\Http\RequestHandler\DummyHandlers\DummyPsrHandler;

class RequestHandlerResolverTest extends TestCase
{
    /**
     * @dataProvider handlersProvider
     */
    public function testResolve(mixed $handler): void
    {
        $resolver = new RequestHandlerResolver(Container::getInstance());
        $result = $resolver->resolve($handler);

        $this->assertIsCallable($result);
    }

    /**
     * @dataProvider invalidHandlersProvider
     */
    public function testInvalidHandlersResolve(mixed $handler): void
    {
        $this->expectException(InvalidRequestHandlerTypeException::class);

        $resolver = new RequestHandlerResolver(Container::getInstance());
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
