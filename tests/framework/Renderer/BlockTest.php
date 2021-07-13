<?php

namespace Tests\framework\Renderer;

use Framework\Renderer\Block;
use Framework\Renderer\Exceptions\RenderClosedBlockException;
use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{
    public function testGetName(): void
    {
        $block = new Block($name = 'block');
        $block->close();

        $this->assertEquals($name, $block->getName());
    }

    public function testClosed(): void
    {
        $block = new Block('block');

        $this->assertFalse($block->isClosed());

        $block->close();

        $this->assertTrue($block->isClosed());
    }

    public function testCloseAlreadyClosed(): void
    {
        $this->expectException(RenderClosedBlockException::class);

        $block = new Block('block');
        $block->close();
        $block->close();
    }

    public function testGetEmptyContent(): void
    {
        $block = new Block('block');
        $block->close();

        $this->assertIsString($block->getContent());
        $this->assertEmpty($block->getContent());
        $this->assertTrue($block->isEmpty());
    }

    public function testGetContent(): void
    {
        $block = new Block('block');
        echo $content = 'Lorem ipsum dolor...';
        $block->close();

        $this->assertFalse($block->isEmpty());
        $this->assertIsString($block->getContent());
        $this->assertEquals($content, $block->getContent());
    }
}
