<?php

namespace Tests\framework\Http\Middleware\MiddlewareDispatcher;

use Framework\Http\Middlewares\MiddlewareDispatcher\Exceptions\InvalidMiddlewareTypeException;
use Framework\Http\Middlewares\MiddlewareDispatcher\Exceptions\UnknownMiddlewareClassException;
use Framework\Http\Middlewares\MiddlewareDispatcher\MiddlewareResolver;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tests\framework\Http\Middleware\DummyMiddlewares\FirstDummyMiddleware;
use Tests\framework\Http\Middleware\DummyMiddlewares\FourthDummyMiddleware;
use Tests\framework\Http\Middleware\DummyMiddlewares\SecondDummyMiddleware;
use Tests\framework\Http\Middleware\DummyMiddlewares\ThirdDummyMiddleware;

class MiddlewareResolverTest extends TestCase
{
    /**
     * @dataProvider middlewaresProvider
     */
    public function testResolve(mixed $middleware): void
    {
        $resolver = new MiddlewareResolver();
        $resolved = $resolver->resolve($middleware);

        $this->assertIsCallable($resolved);
    }

    /**
     * @dataProvider invalidTypeMiddlewaresProvider
     */
    public function testInvalidTypeMiddleware(mixed $middleware): void
    {
        $this->expectException(InvalidMiddlewareTypeException::class);
        $resolver = new MiddlewareResolver();
        $resolver->resolve($middleware);
    }

    /**
     * @dataProvider invalidClassMiddlewaresProvider
     */
    public function testUnknownClassMiddleware(mixed $middleware): void
    {
        $this->expectException(UnknownMiddlewareClassException::class);
        $resolver = new MiddlewareResolver();
        $resolver->resolve($middleware);
    }

    public function middlewaresProvider(): array
    {
        return [
            'Callback middleware' => [function (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
                return $handler->handle($request);
            }],
            'Closure object middleware' => [new FirstDummyMiddleware()],
            'String middleware' => [SecondDummyMiddleware::class],
            'Array middleware' => [
                [ThirdDummyMiddleware::class, FourthDummyMiddleware::class],
            ]
        ];
    }

    public function invalidTypeMiddlewaresProvider(): array
    {
        return [
            'Null value' => [null],
            'Integer value' => [1],
            'Boolean value' => [true],
            'Not exist class' => ['middlewareClass'],
        ];
    }

    public function invalidClassMiddlewaresProvider(): array
    {
        return [
            'Invalid class' => [\stdClass::class],
            'Invalid object' => [new \stdClass()],
            'Invalid parameters' => [function (ServerRequestInterface $request, callable $next): ResponseInterface {
                return $next($request);
            }],
        ];
    }
}
