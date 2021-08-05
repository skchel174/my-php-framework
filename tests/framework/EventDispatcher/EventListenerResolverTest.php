<?php

namespace Tests\framework\EventDispatcher;

use Framework\EventDispatcher\EventListenerResolver;
use Framework\EventDispatcher\EventListenerWrapper;
use Framework\EventDispatcher\Interfaces\EventListenerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class EventListenerResolverTest extends TestCase
{
    private ContainerInterface $container;
    private EventListenerInterface $listener;

    protected function setUp(): void
    {
        $this->listener = $this->getMockBuilder(EventListenerInterface::class)->getMock();
        $this->container = $this->getMockBuilder(ContainerInterface::class)->getMock();
    }

    public function testResolveObject(): void
    {
        $resolver = new EventListenerResolver($this->container);
        $resolved = $resolver->resolve($this->listener);

        $this->assertEquals($resolved, $this->listener);
        $this->assertInstanceOf(EventListenerInterface::class, $resolved);
        $this->assertNotInstanceOf(EventListenerWrapper::class, $resolved);
    }

    public function testResolveString(): void
    {
        $resolver = new EventListenerResolver($this->container);
        $resolved = $resolver->resolve($this->listener::class);

        $this->assertInstanceOf(EventListenerInterface::class, $resolved);
        $this->assertInstanceOf(EventListenerWrapper::class, $resolved);
    }
}
