<?php

namespace Framework\Http\Client\Request;

use Framework\Http\Client\Message\Exceptions\InvalidBodyTypeException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class ServerRequest extends Request implements ServerRequestInterface
{
    protected array $serverParams;
    protected array $cookieParams;
    protected array $queryParams;
    protected array $uploadedFiles;
    protected null|object|array $parsedBody;
    protected array $attributes;

    public function __construct(
        string $method = '',
        null|UriInterface $uri = null,
        array $serverParams = [],
        string $protocolVersion = '',
        array $headers = [],
        null|string|StreamInterface $body = null,
        null|object|array $parsedBody = null,
        array $cookieParams = [],
        array $queryParams = [],
        array $uploadedFiles = [],
        array $attributes = [],
    )
    {
        parent::__construct($method, $uri, $protocolVersion, $headers, $body);
        $this->serverParams = $serverParams;
        $this->cookieParams = $cookieParams;
        $this->queryParams = $queryParams;
        $this->uploadedFiles = $uploadedFiles;
        $this->parsedBody = $parsedBody;
        $this->attributes = $attributes;
    }

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function withCookieParams(array $cookies): static
    {
        $clone = clone $this;
        $clone->cookieParams = $cookies;
        return $clone;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): static
    {
        $clone = clone $this;
        $clone->queryParams = $query;
        return $clone;
    }

    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles): static
    {
        $clone = clone $this;
        $clone->uploadedFiles = $uploadedFiles;
        return $clone;
    }

    public function getParsedBody(): object|array|null
    {
        return $this->parsedBody;
    }

    public function withParsedBody($data): static
    {
        if (!is_null($data) && !is_array($data) && !is_object($data)) {
            throw new InvalidBodyTypeException($data);
        }

        $clone = clone $this;
        $clone->parsedBody = $data;
        return $clone;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute($name, $value): static
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;
        return $clone;
    }

    public function withoutAttribute($name): static
    {
        $clone = clone $this;
        if (isset($this->attributes[$name])) {
            unset($clone->attributes[$name]);
        }
        return $clone;
    }
}