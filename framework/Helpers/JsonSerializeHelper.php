<?php

namespace Framework\Helpers;

trait JsonSerializeHelper
{
    public function jsonSerialize(): array
    {
        $data = [];
        $reflection = new \ReflectionObject($this);

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);

            $name = $property->getName();

            if ($this->isProtected($name)) {
                continue;
            }

            $data[$name] = $property->isInitialized($this) ? $property->getValue($this) : '';

            $property->setAccessible(false);
        }

        return $data;
    }

    protected function protectProperties(): array
    {
        return [];
    }

    protected function isProtected(string $property): bool
    {
        return in_array($property, $this->protectProperties());
    }
}
