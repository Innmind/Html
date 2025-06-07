<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor\Element as ElementFinder,
    Reader,
};
use Innmind\Xml\{
    Element,
    Element\Name,
};
use Innmind\Filesystem\File\Content;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
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

        $h1 = ElementFinder::of('h1')($node)->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertInstanceOf(Element::class, $h1);
        $this->assertSame('h1', $h1->name()->toString());
        $this->assertFalse($h1->children()->empty());
        $this->assertFalse($h1->attributes()->empty());
    }

    public function testReturnNothingWhenElementNotFound()
    {
        $this->assertNull(ElementFinder::of('foo')(Element::of(Name::of('whatever')))->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }
}
