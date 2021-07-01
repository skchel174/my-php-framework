<?php

namespace Tests\framework\Container;

use Framework\Container\Container;
use Framework\Container\Service\FactoryService;
use PHPUnit\Framework\TestCase;
use Tests\framework\Container\DummyServices\DummyService;
use Tests\framework\Container\DummyServices\DummyServiceFactory;

class FactoryServiceTest extends TestCase
{
    public function testConstruct(): void
    {
        $container = new Container();
        $container->set('config.array', $config = ['value01', 'value02', 'value03']);

        $service = new FactoryService(DummyServiceFactory::class);
        $service->argument('config', 'config.array');

        $serviceObject = $service->construct($container);

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
