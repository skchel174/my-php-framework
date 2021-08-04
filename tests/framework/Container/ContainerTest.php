<?php

namespace Tests\framework\Container;

use Framework\Container\Container;
use Framework\Container\Exceptions\InvalidServiceIdException;
use Framework\Container\Exceptions\ServiceNotFoundException;
use Framework\Container\Interfaces\ContainerInterface;
use Framework\Container\Interfaces\ServiceInterface;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    protected ContainerInterface $container;

    public function setUp(): void
    {
        parent::setUp();
        $this->container = new Container();
    }

    /**
     * @dataProvider valuesProvider
     */
    public function testDifferentTypesOfValues(string $id, mixed $value): void
    {
        $this->container->set($id, $value);
        $this->assertEquals($value, $this->container->get($id));
    }

    public function valuesProvider(): array
    {
        return [
            'Integer value' => [ 'id', 34 ],
            'String value' =>  [ 'id', 'string' ],
            'Array value' => [ 'id', ['array'] ],
            'Object value' => [ 'id', new \stdClass() ],
            'True value' => [ 'id', true ],
            'False value' => [ 'id', false ],
        ];
    }

    public function testDeepNestedValues(): void
    {
        $arr = [
            'group01' => [
                'user01' => 'John Doe',
                'user02' => 'Peter Parker',
            ],
            'group02' => [
                'user01' => 'Hugh Jackman',
            ],
            'group03' => [
                'user01' => ['ugly' => 'Artur Morgan'],
            ],
        ];

        $this->container->set('arr.group01.user01', 'John Doe');
        $this->container->set('arr.group01.user02', 'Peter Parker');
        $this->container->set('arr.group02.user01', 'Hugh Jackman');
        $this->container->set('arr.group03.user01.ugly', 'Artur Morgan');

        $this->assertEquals($arr, $this->container->get('arr'));
    }

    public function testServiceShared(): void
    {
        $service = $this->container->get(\stdClass::class);
        $this->assertEquals(false, isset($service->data));

        $service->data = \stdClass::class;

        $service = $this->container->get(\stdClass::class);
        $this->assertNotEmpty($service->data);
        $this->assertEquals(\stdClass::class, $service->data);
    }

    public function testNotSharedService(): void
    {
        $service = $this->getMockBuilder(ServiceInterface::class)
            ->getMock();

        $service->method('isShared')
            ->willReturn(false);

        $this->container->set(ServiceInterface::class, $service);

        $service = $this->container->get(ServiceInterface::class);
        $this->assertEquals(false, isset($service->data));

        $service->data = ServiceInterface::class;

        $service = $this->container->get(ServiceInterface::class);
        $this->assertEquals(false, isset($service->data));
    }

    public function testServiceNotFound(): void
    {
        $this->expectException(ServiceNotFoundException::class);

        $this->container->get('NotExistService');
    }

    /**
     * @dataProvider invalidServicesProvider
     */
    public function testGetInvalidIdException(string $service): void
    {
        $this->expectException(InvalidServiceIdException::class);

        $this->container->set('config', []);
        $this->container->get($service);
    }

    /**
     * @dataProvider invalidServicesProvider
     */
    public function testSetInvalidIdException(string $service): void
    {
        $this->expectException(InvalidServiceIdException::class);

        $this->container->set($service, []);
    }

    public function invalidServicesProvider(): array
    {
        return [
            [''],
            ['.'],
            ['.config'],
            ['config.'],
        ];
    }
}
