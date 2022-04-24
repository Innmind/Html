<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\A;
use Innmind\Xml\{
    Element,
    Attribute,
    Node,
};
use Innmind\Url\Url;
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class ATest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Element::class,
            $a = new A(
                $href = Url::of('http://example.com'),
                Set::of(),
                $child = $this->createMock(Node::class),
            ),
        );
        $this->assertSame('a', $a->name());
        $this->assertSame($href, $a->href());
        $this->assertSame($child, $a->children()->first()->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }

    public function testWithoutAttributes()
    {
        $this->assertTrue(
            (new A(Url::of('http://example.com')))->attributes()->empty(),
        );
    }

    public function testWithoutChildren()
    {
        $this->assertTrue(
            (new A(Url::of('http://example.com')))->children()->empty(),
        );
    }
}
