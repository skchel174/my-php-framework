<?php

namespace Tests\framework\Http\Middlewares\MiddlewareDispatcher;

use Framework\Container\Container;
use Framework\Http\MiddlewareDispatcher\Exceptions\InvalidMiddlewareTypeException;
use Framework\Http\MiddlewareDispatcher\Exceptions\UnknownMiddlewareClassException;
use Framework\Http\MiddlewareDispatcher\MiddlewareResolver;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tests\framework\Http\Middlewares\DummyMiddlewares\FirstDummyMiddleware;
use Tests\framework\Http\Middlewares\DummyMiddlewares\FourthDummyMiddleware;
use Tests\framework\Http\Middlewares\DummyMiddlewares\SecondDummyMiddleware;
use Tests\framework\Http\Middlewares\DummyMiddlewares\ThirdDummyMiddleware;

class MiddlewareResolverTest extends TestCase
{
    /**
     * @dataProvider middlewaresProvider
     */
    public function testResolve(mixed $middleware): void
    {
        $resolver = new MiddlewareResolver(Container::getInstance());
        $resolved = $resolver->resolve($middleware);

        $this->assertIsCallable($resolved);
    }

    /**
     * @dataProvider invalidTypeMiddlewaresProvider
     */
    public function testInvalidTypeMiddleware(mixed $middleware): void
    {
        $this->expectException(InvalidMiddlewareTypeException::class);
        $resolver = new MiddlewareResolver(Container::getInstance());
        $resolver->resolve($middleware);
    }

    /**
     * @dataProvider invalidClassMiddlewaresProvider
     */
    public function testUnknownClassMiddleware(mixed $middleware): void
    {
        $this->expectException(UnknownMiddlewareClassException::class);
        $resolver = new MiddlewareResolver(Container::getInstance());
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
