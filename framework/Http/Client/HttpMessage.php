<?php

namespace Framework\Http\Client;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

abstract class HttpMessage implements MessageInterface
{
    protected string $protocolVersion = '';
    protected array $headers = [];
    protected StreamInterface $body;

    /**
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function withProtocolVersion($version): static
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;
        return $clone;
    }

    /**
     * @return string[][]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasHeader($name): bool
    {
        return !empty($this->getHeader($name));
    }

    /**
     * @param string $name
     * @return string[]
     */
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

    /**
     * @param string $name
     * @return string
     */
    public function getHeaderLine($name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return $this
     */
    public function withHeader($name, $value): static
    {
        $clone = clone $this;
        $clone->headers[$name] = is_array($value) ? $value : [ $value ];
        return $clone;
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return $this
     */
    public function withAddedHeader($name, $value): static
    {
        $header = $this->getHeader($name);
        $value = is_array($value) ? $value : [ $value ];
        return $this->withHeader($name, array_merge($header, $value));
    }

    /**
     * @param string $name
     * @return $this
     */
    public function withoutHeader($name): static
    {
        $clone = clone $this;
        if (!empty($this->getHeader($name))) {
            unset($clone->headers[$name]);
        }
        return $clone;
    }

    /**
     * @return StreamInterface
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * @param StreamInterface $body
     * @return $this
     */
    public function withBody(StreamInterface $body): static
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function fixHeaderCase(string $name): string
    {
        $prep = str_replace('-', ' ', $name);
        return str_replace(' ', '-', ucwords(strtolower($prep)));
    }
}
