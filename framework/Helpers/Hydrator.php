<?php

namespace Framework\Helpers;

class Hydrator
{
    private array $reflections = [];

    public function hydrate(string $class, array $parameters): object
    {
        $reflection = $this->getReflection($class);

        if ($reflection->hasMethod('__construct')) {
            return $this->hydrateConstructor($reflection, $parameters);
        }

        return $this->hydrateProperties($reflection, $parameters);
    }

    public function hydrateCollection(string $class, array $items): array
    {
        return array_map(fn ($item) => $this->hydrate($class, $item), $items);
    }

    public function extract(object $object, array $parameters): array
    {
        $reflection = $this->getReflection($object::class);
        $values = [];

        foreach ($parameters as $key => $parameter) {
            $propertyName = is_string($parameter) ? $parameter : $key;

            if ($reflection->hasProperty($propertyName)) {
                $property = $reflection->getProperty($propertyName);

                if (!$property->isPublic()) {
                    $property->setAccessible(true);
                }

                $value = $property->getValue($object);

                if (is_object($value)) {
                    $value = $this->extract($value, $parameters[$propertyName]);
                }

                $values[$propertyName] = $value;
            }
        }

        return $values;
    }

    private function hydrateProperties(\ReflectionClass $reflection, array $parameters): object
    {
        $instance = $reflection->newInstance();

        foreach ($parameters as $name => $value) {
            if ($reflection->hasProperty($name)) {
                $property = $reflection->getProperty($name);
                if (!$property->isPublic()) {
                    $property->setAccessible(true);
                }
                $property->setValue($instance, $value);
            }
        }
        return $instance;
    }

    private function hydrateConstructor(\ReflectionClass $reflection, array $parameters): object
    {
        $constructor = $reflection->getConstructor();
        $properties = $constructor->getParameters();

        $values = [];

        foreach ($properties as $property) {
            if (array_key_exists($property->getName(), $parameters)) {
                $values[$property->getName()] = $parameters[$property->getName()];
            } elseif ($property->isDefaultValueAvailable()) {
                $values[$property->getName()] = $property->getDefaultValue();
            } else {
                throw new \RuntimeException(
                    'Constructor property "' . $property->getName() . '" was missing during hydration of class ' . $reflection->name
                );
            }
        }

        return $reflection->newInstance(...$values);
    }

    private function getReflection(string $class): \ReflectionClass
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException('Trying to hydrate unknown class; passed ' . $class);
        }

        if (!array_key_exists($class, $this->reflections)) {
            $this->reflections[$class] = new \ReflectionClass($class);
        }
        return $this->reflections[$class];
    }
}
