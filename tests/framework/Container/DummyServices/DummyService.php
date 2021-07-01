<?php

namespace Tests\framework\Container\DummyServices;

class DummyService
{
    public \stdClass $object;
    public array $config;
    public string $default;

    public function __construct(\stdClass $object, array $config, string $default = 'default')
    {
        $this->object = $object;
        $this->config = $config;
        $this->default = $default;
    }
}
