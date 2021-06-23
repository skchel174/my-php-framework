<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Interfaces\RouteInterface;

class Route implements RouteInterface
{
    protected string $path;
    protected mixed $handler;
    protected array $methods;
    protected array $params;
    protected ?string $name;
    protected array $attributes = [];

    public function __construct(
        string $path,
        mixed $handler,
        array $methods,
        array $params = [],
        string $name = null
    )
    {
        $this->path = $path;
        $this->handler = $handler;
        $this->methods = $methods;
        $this->params = $params;
        $this->name = $name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getHandler(): mixed
    {
        return $this->handler;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function name(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getParam(string $id): mixed
    {
        if (!array_key_exists($id, $this->params)) {
            return null;
        }
        return $this->params[$id];
    }

    public function params(array $params): static
    {
        $this->params = $params;
        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function attributes(array $attributes): static
    {
        $this->attributes = $attributes;
        return $this;
    }
}
