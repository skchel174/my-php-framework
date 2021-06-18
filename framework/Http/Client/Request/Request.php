<?php

namespace Framework\Http\Client\Request;

use Framework\Http\Client\Message\HttpMessage;
use Framework\Http\Client\Request\Exceptions\InvalidMethodException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request extends HttpMessage implements RequestInterface
{
    const HTTP_METHODS = [
        'OPTIONS', 'GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'TRACE', 'CONNECT',
    ];

    protected string $method;
    protected ?UriInterface $uri;
    protected string $requestTarget = '';

    public function __construct(
        string $method = '',
        null|UriInterface $uri = null,
        string $protocolVersion = '',
        array $headers = [],
        null|string|StreamInterface $body = null,
    )
    {
        $this->method = $method ? $this->filterMethod($method) : '';
        $this->uri = $uri;
        $this->protocolVersion = $protocolVersion;
        $this->headers = $headers;
        $this->body = $this->normalizeBody($body);
    }

    public function getRequestTarget(): string
    {
        if ($this->requestTarget) {
            return $this->requestTarget;
        }

        if ($this->uri) {
            $target = $this->uri->getPath();
            if ($query = $this->uri->getQuery()) {
                $target .= '?' . $query;
            }
            return  $target;
        }

        return '/';
    }

    public function withRequestTarget($requestTarget): static
    {
        $clone = clone $this;
        $clone->requestTarget = $requestTarget;
        return $clone;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function withMethod($method): static
    {
        $clone = clone $this;
        $clone->method = $this->filterMethod($method);
        return $clone;
    }

    protected function filterMethod(string $method): string
    {
        if (!in_array($method, static::HTTP_METHODS)) {
            throw new InvalidMethodException($method);
        }
        return $method;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): static
    {
        $clone = clone $this;
        $clone->uri = $uri;

        if ($preserveHost) {
            return $clone;
        }

        if (!$uri->getHost()) {
            return $clone;
        }

        $host = $uri->getHost();

        if ($uri->getPort()) {
            $host .= ':' . $uri->getPort();
        }

        $clone->headers['Host'] = [ $host ];
        return $clone;
    }
}
