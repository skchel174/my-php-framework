<?php

namespace Tests\framework\Renderer;

use Framework\Renderer\Renderer;
use Framework\Renderer\TemplatesManager;
use PHPUnit\Framework\TestCase;

class RendererTest extends TestCase
{
    public string $template = __DIR__ . '/templates/template.phtml';
    public array $parameters = ['content' => 'Template content...'];

    public function testRender(): void
    {
        $manager = $this->getMockBuilder(TemplatesManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $manager->expects($this->once())
            ->method('handle')
            ->withConsecutive([$this->template, $this->parameters]);

        $renderer = new Renderer($manager);
        $renderer->render($this->template, $this->parameters);
    }
}
