<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor,
    Reader,
};
use Innmind\Xml\{
    Element,
    Element\Name,
};
use Innmind\Filesystem\File\Content;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class HeadTest extends TestCase
{
    private $read;

    public function setUp(): void
    {
        $this->read = Reader::new();
    }

    public function testExtractHead()
    {
        $node = ($this->read)(
            Content::ofString(\file_get_contents('fixtures/lemonde.html')),
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $head = Visitor\Element::head()($node)->match(
            static fn($head) => $head,
            static fn() => null,
        );

        $this->assertInstanceOf(Element::class, $head);
        $this->assertSame('head', $head->name()->toString());
        $this->assertFalse($head->children()->empty());
        $this->assertTrue($head->attributes()->empty());
    }

    public function testReturnNothingWhenHeadNotFound()
    {
        $this->assertNull(Visitor\Element::head()(Element::of(Name::of('body')))->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }
}
