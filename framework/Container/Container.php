<?php

namespace Framework\Container;

use Framework\Container\Exceptions\InvalidServiceIdException;
use Framework\Container\Interfaces\ContainerInterface;

class Container implements ContainerInterface
{
    protected static ?ContainerInterface $instance = null;
    protected array $services = [];
    protected array $shared = [];

    private function __construct() {}
    private function __clone() {}

    public static function __callStatic(string $name, array $arguments): mixed
    {
        return static::getInstance()->$name(...$arguments);
    }

    public static function getInstance(): ContainerInterface
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->shared)) {
            return $this->shared[$id];
        }

        return $this->getService($id);
    }

    public function has(string $id): bool
    {
        return $this->getService($id) || class_exists($id);
    }

    protected function getService(string $id): mixed
    {
        $keys = explode('.', $id);
        if (empty($keys)) {
            throw new InvalidServiceIdException($id);
        }

        $service = $this->services;

        while (!empty($keys)) {
            $key = array_shift($keys);

            if (!isset($service[$key])) {
                return null;
            }

            $service = $service[$key];
        }
        return $service;
    }

    public function set(string $id, mixed $value): void
    {
        $keys = explode('.', $id);
        if (empty($keys)) {
            throw new InvalidServiceIdException($id);
        }

        $serviceRef =& $this->services;

        while (!empty($keys)) {
            $key = array_shift($keys);

            if (!isset($serviceRef[$key])) {
                $serviceRef[$key] = [];
            }

            if ($keys) {
                $serviceRef =& $serviceRef[$key];
            }
        }
        $serviceRef[$key] = $value;
    }
}
