<?php

namespace Tests\framework\Renderer;

use Framework\Renderer\Exceptions\ExtensionNotExistException;
use Framework\Renderer\Exceptions\InvalidExtensionMethodException;
use Framework\Renderer\ExtensionsCollection;
use PHPUnit\Framework\TestCase;

class ExtensionsCollectionTest extends TestCase
{
    public function testSetExtension(): void
    {
        $extensions = new ExtensionsCollection();

        $extension = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['method01'])
            ->getMock();

        $extension->expects($this->once())
            ->method('method01')
            ->with($this->identicalTo(...$args = ['arg01', 'arg02', 'arg03']))
            ->willReturn($firstResult = 'Called first method');

        $extensions->setExtension($extension, 'method01');

        $this->assertEquals($firstResult, $extensions->call('method01', $args));
    }

    public function testInvalidExtension(): void
    {
        $this->expectException(ExtensionNotExistException::class);

        $extensions = new ExtensionsCollection();

        $extension = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['method01'])
            ->getMock();
        $extensions->setExtension($extension, 'method01');
        $extensions->call('method02');
    }

    public function testInvalidMethod(): void
    {
        $this->expectException(InvalidExtensionMethodException::class);

        $extensions = new ExtensionsCollection();

        $extension = $this->getMockBuilder(\stdClass::class)->getMock();
        $extensions->setExtension($extension, 'method01');
    }
}
