<?php

namespace Tests\framework\Container;

use Framework\Container\Container;
use Framework\Container\Service\CallableService;
use PHPUnit\Framework\TestCase;
use Tests\framework\Container\DummyServices\DummyService;

class CallableServiceTest extends TestCase
{
    public function testConstruct(): void
    {
        $f = function (\stdClass $object, array $config, string $default = 'default'): DummyService {
            return new DummyService($object, $config, $default);
        };

        $container = new Container();
        $container->set('config.array', $config = ['value01', 'value02', 'value03']);

        $service = new CallableService($f);
        $service->argument('config', 'config.array');

        $serviceObject = $service($container);

        $this->assertIsObject($serviceObject);
        $this->assertInstanceOf(DummyService::class, $serviceObject);

        $this->assertIsObject($serviceObject->object);
        $this->assertInstanceOf(\stdClass::class, $serviceObject->object);

        $this->assertIsArray($serviceObject->config);
        $this->assertEquals($config, $serviceObject->config);

        $this->assertNotEmpty($serviceObject->default);
        $this->assertEquals('default', $serviceObject->default);
    }
}
