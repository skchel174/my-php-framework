<?php

namespace Tests\framework\Helpers;

use Framework\Helpers\Hydrator;
use PHPUnit\Framework\TestCase;

class HydratorTest extends TestCase
{
    public function testHydrateConstructor(): void
    {
        $hydrator = new Hydrator();

        $id = 9;
        $name = 'DemoEntity';

        $result = $hydrator->hydrate(Demo01::class, [
            'id' => $id,
            'name' => $name,
        ]);

        $this->assertIsObject($result);
        $this->assertInstanceOf(Demo01::class, $result);

        $this->assertEquals($id, $result->getId());
        $this->assertEquals($name, $result->getName());
    }

    public function testHydrateConstructorWithDefaultProperty(): void
    {
        $hydrator = new Hydrator();

        $id = 9;

        $result = $hydrator->hydrate(Demo01::class, ['id' => $id]);

        $this->assertIsObject($result);
        $this->assertInstanceOf(Demo01::class, $result);

        $this->assertEquals($id, $result->getId());
        $this->assertEquals(Demo01::class, $result->getName());
    }

    public function testHydrateConstructorWithMissingParameter(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectErrorMessage(
            'Constructor property "id" was missing during hydration of class ' . Demo01::class
        );

        $hydrator = new Hydrator();

        $hydrator->hydrate(Demo01::class, []);
    }

    public function testHydrateProperties(): void
    {
        $hydrator = new Hydrator();

        $id = 9;
        $name = 'DemoEntity';

        $result = $hydrator->hydrate(Demo02::class, [
            'id' => $id,
            'name' => $name,
        ]);

        $this->assertIsObject($result);
        $this->assertInstanceOf(Demo02::class, $result);

        $this->assertEquals($id, $result->getId());
        $this->assertEquals($name, $result->getName());
    }

    public function testHydrateDefaultProperty(): void
    {
        $hydrator = new Hydrator();

        $id = 9;

        $result = $hydrator->hydrate(Demo02::class, ['id' => $id]);

        $this->assertIsObject($result);
        $this->assertInstanceOf(Demo02::class, $result);

        $this->assertEquals($id, $result->getId());
        $this->assertEquals(Demo02::class, $result->getName());
    }

    public function testHydrateWithObjectProperty(): void
    {
        $hydrator = new Hydrator();

        $demo01Id = 5;
        $demo02Id = 7;
        $demo01Name = Demo01::class;
        $demo02Name = Demo02::class;

        $result = $hydrator->hydrate(Demo03::class, [
            'demo01' => $hydrator->hydrate(Demo01::class, ['id' => $demo01Id, 'name' => $demo01Name]),
            'demo02' => $hydrator->hydrate(Demo02::class, ['id' => $demo02Id, 'name' => $demo02Name]),
        ]);

        $this->assertIsObject($result);
        $this->assertInstanceOf(Demo03::class, $result);

        $this->assertInstanceOf(Demo01::class, $result->demo01);
        $this->assertEquals($demo01Id, $result->demo01->getId());
        $this->assertEquals($demo01Name, $result->demo01->getName());

        $this->assertInstanceOf(Demo02::class, $result->demo02);
        $this->assertEquals($demo02Id, $result->demo02->getId());
        $this->assertEquals($demo02Name, $result->demo02->getName());
    }

    public function testExtract(): void
    {
        $demo01 = new Demo01($id = 9, $name = 'EntityClass');
        $hydrator = new Hydrator();

        $result = $hydrator->extract($demo01, ['id', 'name']);

        $this->assertIsArray($result);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);

        $this->assertContains($id, $result);
        $this->assertContains($name, $result);
    }

    public function testExtractSelectedProperty(): void
    {
        $demo01 = new Demo01($id = 9, $name = 'EntityClass');
        $hydrator = new Hydrator();

        $result = $hydrator->extract($demo01, ['id']);

        $this->assertIsArray($result);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayNotHasKey('name', $result);

        $this->assertContains($id, $result);
        $this->assertNotContains($name, $result);
    }

    public function testExtractObjectsProperties(): void
    {
        $demo01 = new Demo01($demo01Id = 5, $demo01Name = Demo01::class);

        $demo02 = new Demo02();
        $demo02->setId($demo02Id = 7);
        $demo02->setName($demo02Name = Demo02::class);

        $demo03 = new Demo03($demo01, $demo02);

        $hydrator = new Hydrator();

        $result = $hydrator->extract($demo03, [
            'demo01' => ['id', 'name'],
            'demo02' => ['id'],
        ]);

        $this->assertIsArray($result);

        $this->assertArrayHasKey('demo01', $result);
        $this->assertArrayHasKey('demo02', $result);

        $this->assertArrayHasKey('id', $result['demo01']);
        $this->assertArrayHasKey('name', $result['demo01']);

        $this->assertContains($demo01Id, $result['demo01']);
        $this->assertContains($demo01Name, $result['demo01']);

        $this->assertArrayHasKey('id', $result['demo02']);
        $this->assertArrayNotHasKey('name', $result['demo02']);

        $this->assertContains($demo02Id, $result['demo02']);
        $this->assertNotContains($demo02Name, $result['demo02']);
    }
}

class Demo01
{
    private int $id;
    private string $name;

    public function __construct(int $id, string $name = self::class)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

class Demo02
{
    private int $id;
    private string $name = self::class;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}

class Demo03
{
    public Demo01 $demo01;
    public Demo02 $demo02;

    public function __construct(Demo01 $demo01, Demo02 $demo02)
    {
        $this->demo01 = $demo01;
        $this->demo02 = $demo02;
    }
}
