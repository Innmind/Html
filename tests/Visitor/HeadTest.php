<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor\Head,
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

class HeadTest extends TestCase
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

    public function testExtractHead()
    {
        $node = ($this->read)(
            new Stream(fopen('fixtures/lemonde.html', 'r'))
        );

        $head = (new Head)($node);

        $this->assertInstanceOf(ElementInterface::class, $head);
        $this->assertSame('head', $head->name());
        $this->assertTrue($head->hasChildren());
        $this->assertFalse($head->hasAttributes());
    }

    /**
     * @expectedException Innmind\Html\Exception\ElementNotFound
     */
    public function testThrowWhenHeadNotFound()
    {
        (new Head)(new Element('body'));
    }
}
