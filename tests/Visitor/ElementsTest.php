<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor\Elements,
    Reader\Reader,
};
use Innmind\Xml\{
    Element as ElementInterface,
    Element\Element,
};
use Innmind\Filesystem\File\Content;
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class ElementsTest extends TestCase
{
    private $read;

    public function setUp(): void
    {
        $this->read = Reader::default();
    }

    public function testExtractElement()
    {
        $node = ($this->read)(
            Content::ofString(\file_get_contents('fixtures/lemonde.html')),
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $h1s = Elements::of('h1')($node);

        $this->assertInstanceOf(Set::class, $h1s);
        $this->assertCount(26, $h1s);
    }

    public function testEmptySetWhenNoElementFound()
    {
        $elements = Elements::of('foo')(Element::of('whatever'));

        $this->assertInstanceOf(Set::class, $elements);
        $this->assertCount(0, $elements);
    }
}
