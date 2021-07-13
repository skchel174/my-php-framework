<?php

namespace Tests\framework\Renderer;

use Framework\Renderer\Template;
use Framework\Renderer\TemplatesManager;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    public string $template = __DIR__ . '/templates/template.phtml';
    public array $parameters = ['content' => 'Template content...'];
    public TemplatesManager $manager;

    public function setUp(): void
    {
        $this->manager = $this->getMockBuilder(TemplatesManager::class)
            ->disableOriginalConstructor()
            ->getMock();
    }


    public function testRenderParameters(): void
    {
        $template = new Template($this->template, $this->manager);

        $html = $template->render($this->parameters);

        $this->assertIsString($html);
        $this->assertNotEmpty($html);
        $this->assertStringContainsString('<div class="template">', $html);
        $this->assertStringContainsString($this->parameters['content'], $html);
    }

    public function testInclude(): void
    {
        $this->manager->expects($this->once())
            ->method('includeTemplate')
            ->withConsecutive([$this->template, $this->parameters]);

        $template = new Template($this->template, $this->manager);
        $template->include($this->template, $this->parameters);
    }

    public function testExtends(): void
    {
        $this->manager->expects($this->once())
            ->method('extendTemplate')
            ->withConsecutive([$this->template]);

        $template = new Template($this->template, $this->manager);
        $template->extends($this->template);
    }

    public function testOpenBlock(): void
    {
        $this->manager->expects($this->once())
            ->method('openBlock')
            ->withConsecutive([$name = 'Block01']);

        $template = new Template($this->template, $this->manager);
        $template->block($name);
    }

    public function testCloseBlock(): void
    {
        $this->manager->expects($this->once())
            ->method('closeBlock');

        $template = new Template($this->template, $this->manager);
        $template->close();
    }

    public function testGetValue(): void
    {
        $this->manager->expects($this->once())
            ->method('filter')
            ->withConsecutive([$parameter = 'parameter01', $filters = 'trim|strip']);

        $template = new Template($this->template, $this->manager);
        $template->get($parameter, $filters);
    }

    public function callExtension(): void
    {
        $this->manager->expects($this->once())
            ->method('callExtension')
            ->withConsecutive([$extension = 'extensionMethod', $arguments = ['arg01', 'arg02', 'arg03']]);

        $template = new Template($this->template, $this->manager);
        $template->$extension(...$arguments);
    }
}
