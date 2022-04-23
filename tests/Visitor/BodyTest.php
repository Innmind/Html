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

class BodyTest extends TestCase
{
    private $read;

    public function setUp(): void
    {
        $this->read = Reader::default();
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
