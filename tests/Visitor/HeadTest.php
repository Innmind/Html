<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor\Head,
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
use PHPUnit\Framework\TestCase;

class HeadTest extends TestCase
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

    public function testExtractHead()
    {
        $node = $this->reader->read(
            Stream::fromPath('fixtures/lemonde.html')
        );

        $head = (new Head)($node);

        $this->assertInstanceOf(ElementInterface::class, $head);
        $this->assertSame('head', $head->name());
        $this->assertTrue($head->hasChildren());
        $this->assertFalse($head->hasAttributes());
    }

    /**
     * @expectedException Innmind\Html\Exception\ElementNotFoundException
     */
    public function testThrowWhenHeadNotFound()
    {
        (new Head)(new Element('body'));
    }
}
