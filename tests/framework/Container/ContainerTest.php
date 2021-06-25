<?php

namespace Tests\framework\Container;

use Framework\Container\Container;
use Framework\Container\Interfaces\ContainerInterface;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    protected ContainerInterface $container;

    public function setUp(): void
    {
        parent::setUp();
        $this->container = Container::getInstance();
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
        $id = [
            'value1' => [
                'first' => 'first_value',
                'second' => 'second_value',
            ],
            'value2' => [
                'third' => 'third_value',
            ],
            'value3' => [
                'fourth' => ['key' => 'fourth_value'],
            ],
        ];

        $this->container->set('id.value1.first', 'first_value');
        $this->container->set('id.value1.second', 'second_value');
        $this->container->set('id.value2.third', 'third_value');
        $this->container->set('id.value3.fourth', ['key' => 'fourth_value']);

        $this->assertEquals($id, $this->container->get('id'));
    }
}
