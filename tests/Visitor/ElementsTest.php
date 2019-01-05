<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor\Elements,
    Reader\Reader,
    Translator\NodeTranslators as HtmlTranslators,
    Exception\DomainException,
};
use Innmind\Xml\{
    Element as ElementInterface,
    Element\Element,
    Translator\Translator,
    Translator\NodeTranslators,
};
use Innmind\Stream\Readable\Stream;
use Innmind\Immutable\SetInterface;
use PHPUnit\Framework\TestCase;

class ElementsTest extends TestCase
{
    private $read;

    public function setUp()
    {
        $this->read = new Reader(
            new Translator(
                NodeTranslators::defaults()->merge(
                    HtmlTranslators::defaults()
                )
            )
        );
    }

    public function testThrowWhenEmptyTagName()
    {
        $this->expectException(DomainException::class);

        new Elements('');
    }

    public function testExtractElement()
    {
        $node = ($this->read)(
            new Stream(fopen('fixtures/lemonde.html', 'r'))
        );

        $h1s = (new Elements('h1'))($node);

        $this->assertInstanceOf(SetInterface::class, $h1s);
        $this->assertSame(ElementInterface::class, (string) $h1s->type());
        $this->assertCount(26, $h1s);
    }

    public function testEmptySetWhenNoElementFound()
    {
        $elements = (new Elements('foo'))(new Element('whatever'));

        $this->assertInstanceOf(SetInterface::class, $elements);
        $this->assertSame(ElementInterface::class, (string) $elements->type());
        $this->assertCount(0, $elements);
    }
}
