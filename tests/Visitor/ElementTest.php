<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor\Element as ElementFinder,
    Reader\Reader,
    Exception\DomainException,
    Exception\ElementNotFound,
};
use Innmind\Xml\{
    Element as ElementInterface,
    Element\Element,
};
use Innmind\Filesystem\File\Content;
use Innmind\Stream\Readable\Stream;
use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{
    private $read;

    public function setUp(): void
    {
        $this->read = Reader::default();
    }

    public function testThrowWhenEmptyTagName()
    {
        $this->expectException(DomainException::class);

        new ElementFinder('');
    }

    public function testExtractElement()
    {
        $node = ($this->read)(
            Content\OfStream::of(Stream::of(\fopen('fixtures/lemonde.html', 'r'))),
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $h1 = (new ElementFinder('h1'))($node);

        $this->assertInstanceOf(ElementInterface::class, $h1);
        $this->assertSame('h1', $h1->name());
        $this->assertFalse($h1->children()->empty());
        $this->assertFalse($h1->attributes()->empty());
    }

    public function testThrowWhenElementNotFound()
    {
        $this->expectException(ElementNotFound::class);

        (new ElementFinder('foo'))(Element::of('whatever'));
    }
}
