<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor\Element as ElementFinder,
    Reader\Reader,
    Translator\NodeTranslators as HtmlTranslators,
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

    /**
     * @expectedException Innmind\Html\Exception\InvalidArgumentException
     */
    public function testThrowWhenEmptyTagName()
    {
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
        $this->assertTrue($h1->hasAttributes());
    }

    /**
     * @expectedException Innmind\Html\Exception\ElementNotFound
     */
    public function testThrowWhenElementNotFound()
    {
        (new ElementFinder('foo'))(new Element('whatever'));
    }
}
