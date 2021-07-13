<?php

namespace Framework\Renderer;

class Template
{
    private string $template;
    private TemplatesManager $manager;

    public function __construct(string $template, TemplatesManager $manager)
    {
        $this->template = $template;
        $this->manager = $manager;
    }

    public function render(array $parameters): string
    {
        View::init($this);

        extract($parameters);

        ob_start();
        include $this->template;
        return ob_get_clean();
    }

    public function include(string $template, array $parameters = []): void
    {
        echo $this->manager->includeTemplate($template, $parameters);
    }

    public function extends(string $template): void
    {
        $this->manager->extendTemplate($template);
    }

    public function block(string $name): void
    {
        $this->manager->openBlock($name);
    }

    public function close(): void
    {
        echo $this->manager->closeBlock();
    }

    public function get(mixed $parameter, array|string $filters = []): mixed
    {
        return $this->manager->filter($parameter, $filters);
    }

    public function __call(string $name, array $arguments): mixed
    {
        return $this->manager->callExtension($name, $arguments);
    }
}
