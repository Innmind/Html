<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor\Elements,
    Reader\Reader,
    Exception\DomainException,
};
use Innmind\Xml\{
    Element as ElementInterface,
    Element\Element,
};
use Innmind\Stream\Readable\Stream;
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class ElementsTest extends TestCase
{
    private $read;

    public function setUp(): void
    {
        $this->read = Reader::default();
    }

    public function testThrowWhenEmptyTagName()
    {
        $this->expectException(DomainException::class);

        new Elements('');
    }

    public function testExtractElement()
    {
        $node = ($this->read)(
            new Stream(\fopen('fixtures/lemonde.html', 'r'))
        );

        $h1s = (new Elements('h1'))($node);

        $this->assertInstanceOf(Set::class, $h1s);
        $this->assertSame(ElementInterface::class, $h1s->type());
        $this->assertCount(26, $h1s);
    }

    public function testEmptySetWhenNoElementFound()
    {
        $elements = (new Elements('foo'))(new Element('whatever'));

        $this->assertInstanceOf(Set::class, $elements);
        $this->assertSame(ElementInterface::class, $elements->type());
        $this->assertCount(0, $elements);
    }
}
