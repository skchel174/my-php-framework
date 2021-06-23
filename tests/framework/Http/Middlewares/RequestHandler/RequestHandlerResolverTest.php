<?php

namespace Tests\framework\Http\Middlewares\RequestHandler;

use Framework\Http\Middlewares\RequestHandler\RequestHandlerResolver;
use PHPUnit\Framework\TestCase;
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
        $resolver = new RequestHandlerResolver();
        $result = $resolver->resolve($handler);

        $this->assertIsCallable($result);
    }

    public function handlersProvider(): array
    {
        return [
            'Class name handler' => [DummyPsrHandler::class],
            'Object handler' => [new DummyPsrHandler()],
            'Callable handler' => [DummyCallableHandler::class],
            'Array handler' => [[DummyArrayHandler::class, 'index']],
        ];
    }
}
