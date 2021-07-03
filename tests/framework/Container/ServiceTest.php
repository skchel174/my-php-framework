<?php

namespace Tests\framework\Container;

use Framework\Container\Container;
use Framework\Container\Interfaces\ServiceInterface;
use Framework\Container\Service\Service;
use PHPUnit\Framework\TestCase;
use Tests\framework\Container\DummyServices\DummyService;

class ServiceTest extends TestCase
{
    public function testShared(): void
    {
        $service = new Service(\stdClass::class);

        $this->assertIsBool($service->isShared());
        $this->assertEquals(true, $service->isShared());

        $service = $service->shared(false);

        $this->assertInstanceOf(ServiceInterface::class, $service);
        $this->assertEquals(false, $service->isShared());
    }

    public function testArguments(): void
    {
        $arguments = [
            'first' => \stdClass::class,
            'second' => \stdClass::class,
        ];

        $service = (new Service(\stdClass::class))
            ->argument('first', $arguments['first'])
            ->argument('second', $arguments['second']);

        $this->assertInstanceOf(ServiceInterface::class, $service);
        $this->assertIsArray($service->getArguments());
        $this->assertEquals($arguments, $service->getArguments());
    }

    public function testConstruct(): void
    {
        $container = new Container();
        $container->set('config.array', $config = ['value01', 'value02', 'value03']);

        $service = new Service(DummyService::class);
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
