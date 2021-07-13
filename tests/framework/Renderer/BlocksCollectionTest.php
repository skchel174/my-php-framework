<?php

namespace Tests\framework\Renderer;

use Framework\Renderer\Block;
use Framework\Renderer\BlocksCollection;
use PHPUnit\Framework\TestCase;

class BlocksCollectionTest extends TestCase
{
    public function testOpenBlocksStack(): void
    {
        $blocks = new BlocksCollection();

        $block01 = $this->getMockBuilder(Block::class)->disableOriginalConstructor()->getMock();
        $block02 = $this->getMockBuilder(Block::class)->disableOriginalConstructor()->getMock();
        $block03 = $this->getMockBuilder(Block::class)->disableOriginalConstructor()->getMock();

        $blocks->setOpen($block01);
        $blocks->setOpen($block02);
        $blocks->setOpen($block03);

        $this->assertEquals($block03, $blocks->getOpen());
        $this->assertEquals($block02, $blocks->getOpen());
        $this->assertEquals($block01, $blocks->getOpen());
    }

    public function testIsNotClosedExist(): void
    {
        $blocks = new BlocksCollection();

        $block = $this->getMockBuilder(Block::class)->disableOriginalConstructor()->getMock();

        $blocks->setOpen($block);
        $this->assertTrue($blocks->isNotClosedExist());

        $blocks->getOpen();
        $this->assertFalse($blocks->isNotClosedExist());
    }

    public function testBlocksStorage()
    {
        $blocks = new BlocksCollection();

        $block = $this->getMockBuilder(Block::class)->disableOriginalConstructor()->getMock();
        $block->method('getName')->willReturn($name = 'block01');

        $this->assertFalse($blocks->has($block));
        $this->assertNull($blocks->get($name));

        $blocks->set($block);

        $this->assertTrue($blocks->has($block));
        $this->assertEquals($block, $blocks->get($name));
    }
}
