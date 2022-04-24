<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor,
    Reader\Reader,
};
use Innmind\Xml\{
    Element as ElementInterface,
    Element\Element,
};
use Innmind\Filesystem\File\Content;
use Innmind\Stream\Readable\Stream;
use PHPUnit\Framework\TestCase;

class HeadTest extends TestCase
{
    private $read;

    public function setUp(): void
    {
        $this->read = Reader::default();
    }

    public function testExtractHead()
    {
        $node = ($this->read)(
            Content\OfStream::of(Stream::of(\fopen('fixtures/lemonde.html', 'r'))),
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $head = Visitor\Element::head()($node)->match(
            static fn($head) => $head,
            static fn() => null,
        );

        $this->assertInstanceOf(ElementInterface::class, $head);
        $this->assertSame('head', $head->name());
        $this->assertFalse($head->children()->empty());
        $this->assertTrue($head->attributes()->empty());
    }

    public function testReturnNothingWhenHeadNotFound()
    {
        $this->assertNull(Visitor\Element::head()(Element::of('body'))->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }
}
