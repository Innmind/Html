<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor,
    Reader\Reader,
    Translator\NodeTranslators as HtmlTranslators,
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

class HeadTest extends TestCase
{
    private $read;

    public function setUp(): void
    {
        $this->read = Reader::of(
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
            new Stream(\fopen('fixtures/lemonde.html', 'r'))
        );

        $head = Visitor\Element::head()($node);

        $this->assertInstanceOf(ElementInterface::class, $head);
        $this->assertSame('head', $head->name());
        $this->assertTrue($head->hasChildren());
        $this->assertTrue($head->attributes()->empty());
    }

    public function testThrowWhenHeadNotFound()
    {
        $this->expectException(ElementNotFound::class);

        Visitor\Element::head()(new Element('body'));
    }
}
