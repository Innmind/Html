<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Visitor;

use Innmind\Html\{
    Visitor,
    Reader\Reader,
    Exception\ElementNotFound,
};
use Innmind\Xml\{
    Element as ElementInterface,
    Element\Element,
};
use Innmind\Stream\Readable\Stream;
use PHPUnit\Framework\TestCase;

class HeadTest extends TestCase
{
    private $read;

    public function setUp(): void
    {
        $this->read = Reader::default();
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
