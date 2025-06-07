<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor\Elements,
    Reader,
};
use Innmind\Xml\{
    Element,
    Element\Name,
};
use Innmind\Filesystem\File\Content;
use Innmind\Immutable\Sequence;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ElementsTest extends TestCase
{
    private $read;

    public function setUp(): void
    {
        $this->read = Reader::new();
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

        $this->assertInstanceOf(Sequence::class, $h1s);
        $this->assertCount(26, $h1s);
    }

    public function testEmptySetWhenNoElementFound()
    {
        $elements = Elements::of('foo')(Element::of(Name::of('whatever')));

        $this->assertInstanceOf(Sequence::class, $elements);
        $this->assertCount(0, $elements);
    }
}
