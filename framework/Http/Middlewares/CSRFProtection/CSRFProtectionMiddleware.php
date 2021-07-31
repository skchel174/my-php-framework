<?php

namespace Framework\Http\Middlewares\CSRFProtection;

use Framework\Http\Router\Interfaces\RouteInterface;
use Framework\Http\Sessions\Interfaces\SessionInterface;
use Framework\Services\CSRFProtection\CSRFToken;
use Framework\Services\CSRFProtection\Exceptions\FailedValidationCSRFTokenException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CSRFProtectionMiddleware implements MiddlewareInterface
{
    private array $expectedMethods;
    private array $expectedRoutes;
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->expectMethods();
        $this->expectRoutes();
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->checkRequest($request)) {
            return $handler->handle($request);
        }
        throw new FailedValidationCSRFTokenException();
    }

    protected function expectMethods(): void
    {
        $this->expectedMethods = [
            'GET', 'HEAD', 'OPTIONS', 'TRACE',
        ];
    }

    protected function expectRoutes(): void
    {
        $this->expectedRoutes = [];
    }

    protected function checkRequest(ServerRequestInterface $request): bool
    {
        return $this->isGetRequest($request)
            || $this->isExpectedRoute($request)
            || $this->isTokenMatch($request);
    }

    protected function isGetRequest(ServerRequestInterface $request): bool
    {
        $method = $request->getMethod();
        return in_array($method, $this->expectedMethods);
    }

    protected function isExpectedRoute(ServerRequestInterface $request): bool
    {
        $route = $request->getAttribute(RouteInterface::class);
        $path = $route->getName() ?: $route->getPath();
        return in_array($path, $this->expectedRoutes);
    }

    protected function isTokenMatch(ServerRequestInterface $request): bool
    {
        $sessionToken = $this->session->get(CSRFToken::KEY);
        $clientToken = $request->getParsedBody()[CSRFToken::KEY]
            ?? $request->getHeaderLine(CSRFToken::HEADER);

        return !empty($sessionToken) && !empty($clientToken) && $sessionToken === $clientToken;
    }
}
