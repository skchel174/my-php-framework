<?php

namespace Tests\framework\Container\DummyServices;

class DummyServiceFactory
{
    public function __invoke(\stdClass $object, array $config, string $default = 'default'): DummyService
    {
        return new DummyService($object, $config, $default);
    }
}
