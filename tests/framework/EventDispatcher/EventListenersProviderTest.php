<?php

namespace Tests\framework\EventDispatcher;

use Framework\EventDispatcher\EventListenerResolver;
use Framework\EventDispatcher\EventListenersProvider;
use Framework\EventDispatcher\Exceptions\UnknownEventException;
use Framework\EventDispatcher\Interfaces\EventInterface;
use Framework\EventDispatcher\Interfaces\EventListenerInterface;
use PHPUnit\Framework\TestCase;

class EventListenersProviderTest extends TestCase
{
    public EventInterface $event01;
    public EventInterface $event02;

    public EventListenerInterface $listener01;
    public EventListenerInterface $listener02;
    public EventListenerInterface $listener03;

    public EventListenerResolver $resolver;

    public function setUp(): void
    {
        $this->event01 = $this->getMockBuilder(EventInterface::class)->getMock();
        $this->event02 = $this->getMockBuilder(EventInterface::class)->getMock();

        $this->listener01 = $this->getMockBuilder(EventListenerInterface::class)->getMock();
        $this->listener02 = $this->getMockBuilder(EventListenerInterface::class)->getMock();
        $this->listener03 = $this->getMockBuilder(EventListenerInterface::class)->getMock();

        $this->resolver= $this->getMockBuilder(EventListenerResolver::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testHas(): void
    {
        $provider = new EventListenersProvider($this->resolver);

        $this->assertFalse($provider->has($this->event01::class));

        $provider->subscribe($this->event01::class, $this->listener01::class);

        $this->assertTrue($provider->has($this->event01::class));
    }

    public function testGetOneListener(): void
    {
        $this->resolver->expects($this->once())
            ->method('resolve')
            ->withConsecutive([$this->listener01])
            ->willReturn($this->listener01);

        $provider = new EventListenersProvider($this->resolver);
        $provider->subscribe($this->event01::class, $this->listener01);
        $listeners = $provider->get($this->event01::class);

        $this->assertIsArray($listeners);
        $this->assertNotEmpty($listeners);
        $this->assertCount(1, $listeners);
        $this->assertContainsOnlyInstancesOf(EventListenerInterface::class, $listeners);
    }

    public function testGetAnyListeners(): void
    {
        $this->resolver->expects($this->exactly(3))
            ->method('resolve');

        $provider = new EventListenersProvider($this->resolver);

        $provider->subscribe($this->event01::class, $arr = [
            $this->listener01::class,
            $this->listener02::class,
            $this->listener03::class,
        ]);

        $listeners = $provider->get($this->event01::class);

        $this->assertIsArray($listeners);
        $this->assertNotEmpty($listeners);
        $this->assertCount(count($arr), $listeners);
        $this->assertContainsOnlyInstancesOf(EventListenerInterface::class, $listeners);
    }

    public function testGetUnknownEventException(): void
    {
        $this->expectException(UnknownEventException::class);

        $provider = new EventListenersProvider($this->resolver);
        $provider->get(DemoEvent01::class);
    }
}
