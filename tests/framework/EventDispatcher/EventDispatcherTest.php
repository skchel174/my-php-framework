<?php

namespace Tests\framework\EventDispatcher;

use Framework\EventDispatcher\EventDispatcher;
use Framework\EventDispatcher\EventListenersProvider;
use Framework\EventDispatcher\Interfaces\EventInterface;
use Framework\EventDispatcher\Interfaces\EventListenerInterface;
use PHPUnit\Framework\TestCase;

class EventDispatcherTest extends TestCase
{
    public function testDispatch(): void
    {
        $map = [
            [
                $event01 = $this->getMockBuilder(EventInterface::class)->getMock(),
                [
                    $listener01 = $this->getMockBuilder(EventListenerInterface::class)->getMock(),
                    $listener02 = $this->getMockBuilder(EventListenerInterface::class)->getMock(),
                ]
            ],
            [
                $event02 = $this->getMockBuilder(EventInterface::class)->getMock(),
                [
                    $listener03 = $this->getMockBuilder(EventListenerInterface::class)->getMock(),
                ]
            ],
        ];

        $listener01->expects($this->once())
            ->method('handle')
            ->withConsecutive([$event01]);

        $listener02->expects($this->once())
            ->method('handle')
            ->withConsecutive([$event01]);

        $listener03->expects($this->once())
            ->method('handle')
            ->withConsecutive([$event02]);

        $provider = $this->getMockBuilder(EventListenersProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $provider->expects($this->exactly(count($map)))
            ->method('has')
            ->withConsecutive(
                [$event01],
                [$event02],
            )
            ->willReturn(true);

        $provider->expects($this->exactly(count($map)))
            ->method('get')
            ->withConsecutive(
                [$event01],
                [$event02],
            )
            ->will($this->returnValueMap($map));

        $dispatcher = new EventDispatcher($provider);
        $dispatcher->dispatch($event01);
        $dispatcher->dispatch($event02);
    }
}
