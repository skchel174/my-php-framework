<?php

namespace Tests\framework\Renderer;

use Framework\Renderer\Block;
use Framework\Renderer\BlocksCollection;
use Framework\Renderer\Exceptions\InvalidTemplateFileException;
use Framework\Renderer\Exceptions\NotOpenedBlockException;
use Framework\Renderer\ExtensionsCollection;
use Framework\Renderer\FiltersCollection;
use Framework\Renderer\Template;
use Framework\Renderer\TemplatesManager;
use PHPUnit\Framework\TestCase;

class TemplatesManagerTest extends TestCase
{
    public string $directory = __DIR__ . '/templates';
    public FiltersCollection $filters;
    public ExtensionsCollection $extensions;
    public BlocksCollection $blocks;
    public TemplatesManager $manager;

    public function setUp(): void
    {
        $this->filters = $this->getMockBuilder(FiltersCollection::class)->getMock();
        $this->extensions = $this->getMockBuilder(ExtensionsCollection::class)->getMock();
        $this->blocks = $this->getMockBuilder(BlocksCollection::class)->getMock();
        $this->manager = new TemplatesManager($this->directory, $this->blocks, $this->filters, $this->extensions);
    }

    public function testCreateTemplate(): void
    {
        $template = $this->manager->createTemplate('template.phtml');

        $this->assertInstanceOf(Template::class, $template);
    }

    public function testCreateNotExistTemplate(): void
    {
        $this->expectException(InvalidTemplateFileException::class);

        $this->manager->createTemplate('template');
    }

    public function testRenderTemplate(): void
    {
        $template = $this->getMockBuilder(Template::class)
            ->disableOriginalConstructor()
            ->getMock();

        $template->expects($this->once())
            ->method('render')
            ->withConsecutive([$parameters = ['content' => 'Template content...']])
            ->willReturn($parameters['content']);

        $result = $this->manager->renderTemplate($template, $parameters);

        $this->assertIsString($result);
        $this->assertEquals($parameters['content'], $result);
    }

    public function testOpenBlock(): void
    {
        $this->blocks->expects($this->once())
            ->method('setOpen')
            ->with($this->callback(fn($parameter) => $parameter instanceof Block));

        $this->manager->openBlock('block01');
        ob_end_clean();
    }

    public function testCloseExistBlock(): void
    {
        $block = $this->getMockBuilder(Block::class)->disableOriginalConstructor()->getMock();

        $block->method('getName')->willReturn('block01');
        $block->method('content')->withConsecutive([$content = 'Block content...']);
        $block->method('getContent')->willReturn($content);

        $this->blocks->method('get')->willReturn($block);
        $this->blocks->method('getOpen')->willReturn($block);
        $this->blocks->expects($this->once())->method('has')->willReturn(true);

        $result = $this->manager->closeBlock();

        $this->assertIsString($result);
        $this->assertEquals($result, $content);
    }

    public function testCloseNotExistBlock(): void
    {
        $block = $this->getMockBuilder(Block::class)->disableOriginalConstructor()->getMock();
        $block->method('getContent')->willReturn($content = 'Block content...');

        $this->blocks->method('getOpen')->willReturn($block);
        $this->blocks->method('set')->withConsecutive([$block]);
        $this->blocks->expects($this->once())->method('has')->willReturn(false);

        $result = $this->manager->closeBlock();

        $this->assertIsString($result);
        $this->assertEquals($result, $content);
    }

    public function testCloseNotOpenedBlock(): void
    {
        $this->expectException(NotOpenedBlockException::class);

        $this->blocks->method('getOpen')->willReturn(null);
        $this->manager->closeBlock();
    }

    public function testFilter(): void
    {
        $this->filters->expects($this->once())
            ->method('handle')
            ->withConsecutive([$parameter = 'parameter01', $filters = 'trim|strip'])
            ->willReturn($parameter);

        $result = $this->manager->filter($parameter, $filters);

        $this->assertEquals($parameter, $result);
    }

    public function testCallExtension(): void
    {
        $this->extensions->expects($this->once())
            ->method('call')
            ->withConsecutive([$name = 'parameter01', $arguments = ['arg01', 'arg02', 'arg03']])
            ->willReturn($name);

        $result = $this->manager->callExtension($name, $arguments);

        $this->assertEquals($name, $result);
    }
}
