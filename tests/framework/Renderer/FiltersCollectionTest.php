<?php

namespace Tests\framework\Renderer;

use Framework\Renderer\Exceptions\InvalidFilterException;
use Framework\Renderer\FiltersCollection;
use PHPUnit\Framework\TestCase;

class FiltersCollectionTest extends TestCase
{
    public function testHandleValue(): void
    {
        $str = 'content data';
        $filters = new FiltersCollection();

        $this->assertEquals(ucfirst($str), $filters->handle($str, 'ucfirst'));
        $this->assertEquals(lcfirst($str), $filters->handle($str, 'lcfirst'));

        $this->assertEquals(ucwords($str), $filters->handle($str, 'title'));

        $this->assertEquals(strtoupper($str), $filters->handle($str, 'upper'));
        $this->assertEquals(strtolower($str), $filters->handle($str, 'lower'));

        $this->assertEquals(
            htmlspecialchars("<div>$str</div>"),
            $filters->handle("<div>$str</div>", 'escape')
        );

        $this->assertEquals(
            strip_tags("<div>$str</div>"),
            $filters->handle("<div>$str</div>", 'strip')
        );

        $this->assertEquals(
            ucfirst(strip_tags("<div>$str</div>")),
            $filters->handle("<div>$str</div>", 'strip|ucfirst')
        );
    }

    public function testInvalidFilter(): void
    {
        $this->expectException(InvalidFilterException::class);

        $filters = new FiltersCollection();
        $filters->handle('ucwords', 'value');
    }
}
