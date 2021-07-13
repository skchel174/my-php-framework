<?php

namespace Framework\Renderer;

use Framework\Renderer\Exceptions\ExtensionNotExistException;
use Framework\Renderer\Exceptions\InvalidExtensionMethodException;

class ExtensionsCollection
{
    private array $extensions;

    public function call(string $method, array $arguments = []): mixed
    {
        $extension = $this->getExtension($method);
        return $extension->$method(...$arguments);
    }

    public function setExtension(object $extension, string|array $methods): void
    {
        $methods = is_string($methods) ? [ $methods ] : $methods;

        $this->methodsExistGuard($extension, $methods);

        foreach ($methods as $method) {
            $this->extensions[$method] = $extension;
        }
    }

    protected function getExtension(string $method): object
    {
        if (!array_key_exists($method, $this->extensions)) {
            throw new ExtensionNotExistException($method);
        }
        return $this->extensions[$method];
    }

    protected function methodsExistGuard(object $extension, array $methods): void
    {
        foreach ($methods as $method) {
            if (!method_exists($extension, $method)) {
                throw new InvalidExtensionMethodException($extension, $method);
            }
        }
    }
}
