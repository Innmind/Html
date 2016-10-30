<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor\Elements,
    Reader\Reader,
    Translator\NodeTranslators as HtmlTranslators
};
use Innmind\Xml\{
    ElementInterface,
    Element\Element,
    Translator\NodeTranslator,
    Translator\NodeTranslators
};
use Innmind\Filesystem\Stream\Stream;
use Innmind\Immutable\SetInterface;

class ElementsTest extends \PHPUnit_Framework_TestCase
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
        new Elements('');
    }

    public function testExtractElement()
    {
        $node = $this->reader->read(
            Stream::fromPath('fixtures/lemonde.html')
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
