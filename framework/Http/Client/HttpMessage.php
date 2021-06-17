<?php

namespace Framework\Http\Client;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

abstract class HttpMessage implements MessageInterface
{
    protected string $protocolVersion = '';
    protected array $headers = [];
    protected ?StreamInterface $body = null;

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): static
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;
        return $clone;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return !empty($this->getHeader($name));
    }

    public function getHeader($name): array
    {
        $name = $this->fixHeaderCase($name);
        foreach ($this->headers as $key => $header) {
            if ($name === $this->fixHeaderCase($key)) {
                return $header;
            }
        }
        return [];
    }

    public function getHeaderLine($name): string
    {
        return implode(',', $this->getHeader($name));
    }

    public function withHeader($name, $value): static
    {
        $clone = clone $this;
        $clone->headers[$name] = is_array($value) ? $value : [ $value ];
        return $clone;
    }

    public function withAddedHeader($name, $value): static
    {
        $header = $this->getHeader($name);
        $value = is_array($value) ? $value : [ $value ];
        return $this->withHeader($name, array_merge($header, $value));
    }

    public function withoutHeader($name): static
    {
        $clone = clone $this;
        if (!empty($this->getHeader($name))) {
            unset($clone->headers[$name]);
        }
        return $clone;
    }

    public function getBody(): ?StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): static
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }

    protected function fixHeaderCase(string $name): string
    {
        $prep = str_replace('-', ' ', $name);
        return str_replace(' ', '-', ucwords(strtolower($prep)));
    }
}
