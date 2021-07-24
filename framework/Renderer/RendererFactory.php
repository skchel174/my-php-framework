<?php

namespace Framework\Renderer;

use Framework\Renderer\Extensions\AssetsDispatcher;
use Framework\Renderer\Interfaces\RendererInterface;
use Psr\Container\ContainerInterface;

class RendererFactory
{
    public function __invoke(ContainerInterface $container): RendererInterface
    {
        $extensions = $container->get(ExtensionsCollection::class);
        $this->extensions($container, $extensions);

        $templatesManager = new TemplatesManager(
            $container->get('config.templates'),
            $container->get(BlocksCollection::class),
            $container->get(FiltersCollection::class),
            $extensions,
        );

        return new Renderer($templatesManager);
    }

    protected function extensions(ContainerInterface $container, ExtensionsCollection $extensions): void
    {
        $extensions->setExtension($container->get(AssetsDispatcher::class), 'assets');
    }
}
