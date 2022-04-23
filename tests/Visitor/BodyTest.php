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

class BodyTest extends TestCase
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

    public function testExtractBody()
    {
        $node = ($this->read)(
            new Stream(\fopen('fixtures/lemonde.html', 'r'))
        );

        $body = Visitor\Element::body()($node);

        $this->assertInstanceOf(ElementInterface::class, $body);
        $this->assertSame('body', $body->name());
        $this->assertTrue($body->hasChildren());
        $this->assertFalse($body->attributes()->empty());
    }

    public function testThrowWhenBodyNotFound()
    {
        $this->expectException(ElementNotFound::class);

        Visitor\Element::body()(new Element('head'));
    }
}
