<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor\Body,
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

class BodyTest extends TestCase
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

    public function testExtractBody()
    {
        $node = $this->reader->read(
            new Stream(fopen('fixtures/lemonde.html', 'r'))
        );

        $body = (new Body)($node);

        $this->assertInstanceOf(ElementInterface::class, $body);
        $this->assertSame('body', $body->name());
        $this->assertTrue($body->hasChildren());
        $this->assertTrue($body->hasAttributes());
    }

    /**
     * @expectedException Innmind\Html\Exception\ElementNotFoundException
     */
    public function testThrowWhenBodyNotFound()
    {
        (new Body)(new Element('head'));
    }
}
