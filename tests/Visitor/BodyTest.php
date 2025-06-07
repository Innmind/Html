<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor,
    Reader\Reader,
};
use Innmind\Xml\{
    Element,
    Element\Name,
};
use Innmind\Filesystem\File\Content;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class BodyTest extends TestCase
{
    private $read;

    public function setUp(): void
    {
        $this->read = Reader::new();
    }

    public function testExtractBody()
    {
        $node = ($this->read)(
            Content::ofString(\file_get_contents('fixtures/lemonde.html')),
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $body = Visitor\Element::body()($node)->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertInstanceOf(Element::class, $body);
        $this->assertSame('body', $body->name()->toString());
        $this->assertFalse($body->children()->empty());
        $this->assertFalse($body->attributes()->empty());
    }

    public function testReturnNothingWhenBodyNotFound()
    {
        $this->assertNull(Visitor\Element::body()(Element::of(Name::of('head')))->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }
}
