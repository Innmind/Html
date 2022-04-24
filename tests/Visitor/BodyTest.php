<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor,
    Reader\Reader,
    Exception\ElementNotFound,
};
use Innmind\Xml\{
    Element as ElementInterface,
    Element\Element,
};
use Innmind\Filesystem\File\Content;
use Innmind\Stream\Readable\Stream;
use PHPUnit\Framework\TestCase;

class BodyTest extends TestCase
{
    private $read;

    public function setUp(): void
    {
        $this->read = Reader::default();
    }

    public function testExtractBody()
    {
        $node = ($this->read)(
            Content\OfStream::of(Stream::of(\fopen('fixtures/lemonde.html', 'r'))),
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $body = Visitor\Element::body()($node);

        $this->assertInstanceOf(ElementInterface::class, $body);
        $this->assertSame('body', $body->name());
        $this->assertFalse($body->children()->empty());
        $this->assertFalse($body->attributes()->empty());
    }

    public function testThrowWhenBodyNotFound()
    {
        $this->expectException(ElementNotFound::class);

        Visitor\Element::body()(Element::of('head'));
    }
}
