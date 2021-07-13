<?php

namespace Tests\framework\Renderer;

use Framework\Renderer\BlocksCollection;
use Framework\Renderer\ExtensionsCollection;
use Framework\Renderer\FiltersCollection;
use Framework\Renderer\Interfaces\RendererInterface;
use Framework\Renderer\Renderer;
use Framework\Renderer\TemplatesManager;
use PHPUnit\Framework\TestCase;

class RendererFunctionTest extends TestCase
{
    public array $parameters = [
        'content' => 'content template',
        'list' => [
            'Lorem ipsum dolor sit amet.',
            'Commodi, delectus',
        ],
    ];
    public RendererInterface $renderer;

    public function setUp(): void
    {
        $extensions = new ExtensionsCollection();
        $extensions->setExtension(new \DateTime(), 'format');

        $templatesManager = new TemplatesManager(
            __DIR__ . '/templates',
            new FiltersCollection(),
            $extensions,
            new BlocksCollection(),
        );

        $this->renderer = new Renderer($templatesManager);
    }

    public function testRenderFullTemplate(): void
    {
        $html = $this->renderer->render('layout.phtml');

        $this->assertIsString($html);
        $this->assertNotEmpty($html);

        $this->assertStringStartsWith('<!doctype html>', $html);
        $this->assertStringEndsWith('</html>', $html);
    }

    public function testRenderBlockWithContent(): void
    {
        $html = $this->renderer->render('layout.phtml');

        $this->assertStringContainsString('<title>Layout Title</title>', $html);

        $this->assertStringContainsString('<main class="main">', $html);
        $this->assertStringContainsString('</main>', $html);
        $this->assertStringContainsString('<h1>Layout Main</h1>', $html);
    }

    public function testRenderIncludeTemplateWithBlock(): void
    {
        $html = $this->renderer->render('layout.phtml');

        $this->assertStringContainsString('<header>', $html);
        $this->assertStringContainsString('</header>', $html);
        $this->assertStringContainsString('<h1>Renderer Tests</h1>', $html);
    }

    public function testRenderIncludeTemplateWithoutBlock(): void
    {
        $html = $this->renderer->render('layout.phtml');

        $this->assertStringContainsString('<nav>', $html);
        $this->assertStringContainsString('</nav>', $html);
        $this->assertStringContainsString('#link01', $html);
        $this->assertStringContainsString('#link02', $html);
    }

    public function testRenderExtendsTemplate(): void
    {
        $html = $this->renderer->render('content.phtml', $this->parameters);

        $this->assertStringStartsWith('<!doctype html>', $html);
        $this->assertStringEndsWith('</html>', $html);

        $this->assertStringContainsString('<title>Content Title</title>', $html);
        $this->assertStringContainsString('<main class="main_extends">', $html);

        $this->assertStringNotContainsString('<title>Layout Title</title>', $html);
        $this->assertStringNotContainsString('<h1>Layout Main</h1>', $html);
    }

    public function testRenderParameters(): void
    {
        $html = $this->renderer->render('content.phtml', $this->parameters);

        $this->assertStringContainsString(ucwords($this->parameters['content']), $html);
        $this->assertStringContainsString($this->parameters['list'][0], $html);
        $this->assertStringContainsString($this->parameters['list'][1], $html);
    }

    public function testRenderExtension(): void
    {
        $html = $this->renderer->render('layout.phtml');

        $this->assertStringContainsString((new \DateTime)->format('Y-m-d H:i'), $html);
    }
}
