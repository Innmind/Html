<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor\Element as ElementFinder,
    Reader\Reader,
    Translator\NodeTranslators as HtmlTranslators
};
use Innmind\Xml\{
    ElementInterface,
    Element\Element,
    Translator\NodeTranslator,
    Translator\NodeTranslators
};
use Innmind\Stream\Readable\Stream;
use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{
    private $reader;

    public function setUp()
    {
        $this->reader = new Reader(
            new NodeTranslator(
                NodeTranslators::defaults()->merge(
                    HtmlTranslators::defaults()
                )
            )
        );
    }

    /**
     * @expectedException Innmind\Html\Exception\InvalidArgumentException
     */
    public function testThrowWhenEmptyTagName()
    {
        new ElementFinder('');
    }

    public function testExtractElement()
    {
        $node = $this->reader->read(
            new Stream(fopen('fixtures/lemonde.html', 'r'))
        );

        $h1 = (new ElementFinder('h1'))($node);

        $this->assertInstanceOf(ElementInterface::class, $h1);
        $this->assertSame('h1', $h1->name());
        $this->assertTrue($h1->hasChildren());
        $this->assertTrue($h1->hasAttributes());
    }

    /**
     * @expectedException Innmind\Html\Exception\ElementNotFoundException
     */
    public function testThrowWhenElementNotFound()
    {
        (new ElementFinder('foo'))(new Element('whatever'));
    }
}
