<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor\Element as ElementFinder,
    Reader\Reader,
    Translator\NodeTranslators as HtmlTranslators,
    Exception\DomainException,
    Exception\ElementNotFound,
};
use Innmind\Xml\{
    Element as ElementInterface,
    Element\Element,
    Translator\Translator,
    Translator\NodeTranslators,
};
use Innmind\Stream\Readable\Stream;
use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{
    private $read;

    public function setUp(): void
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

        new ElementFinder('');
    }

    public function testExtractElement()
    {
        $node = ($this->read)(
            new Stream(fopen('fixtures/lemonde.html', 'r'))
        );

        $h1 = (new ElementFinder('h1'))($node);

        $this->assertInstanceOf(ElementInterface::class, $h1);
        $this->assertSame('h1', $h1->name());
        $this->assertTrue($h1->hasChildren());
        $this->assertFalse($h1->attributes()->empty());
    }

    public function testThrowWhenElementNotFound()
    {
        $this->expectException(ElementNotFound::class);

        (new ElementFinder('foo'))(new Element('whatever'));
    }
}
