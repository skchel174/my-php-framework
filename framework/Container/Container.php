<?php

namespace Framework\Container;

use Framework\Container\Exceptions\InvalidServiceIdException;
use Framework\Container\Exceptions\ServiceNotFoundException;
use Framework\Container\Interfaces\ContainerInterface;
use Framework\Container\Interfaces\ServiceInterface;
use Framework\Container\Service\Service;

class Container implements ContainerInterface
{
    protected array $services = [];
    protected array $shared = [];

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->shared)) {
            return $this->shared[$id];
        }

        $service = $this->getService($id);

        if ($service === null) {
            if (!class_exists($id)) {
                throw new ServiceNotFoundException($id);
            }
            $service = new Service($id);
        }

        if ($service instanceof ServiceInterface) {
            $serviceObject = $service->construct($this);
            if ($service->isShared()) {
                $this->shared[$id] = $serviceObject;
            }
            return $serviceObject;
        }

        return $service;
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
