<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\A;
use Innmind\Xml\{
    Element,
    Node,
};
use Innmind\Url\Url;
use Innmind\Immutable\Sequence;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ATest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Element\Custom::class,
            $a = A::of(
                $href = Url::of('http://example.com'),
                Sequence::of(),
                Sequence::of($child = Node::text('')),
            ),
        );
        $this->assertSame('a', $a->normalize()->name()->toString());
        $this->assertSame($href, $a->href());
        $this->assertSame($child, $a->normalize()->children()->first()->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }

    public function testWithoutAttributes()
    {
        $this->assertFalse(
            A::of(Url::of('http://example.com'))->normalize()->attributes()->empty(),
        );
    }

    public function testWithoutChildren()
    {
        $this->assertTrue(
            A::of(Url::of('http://example.com'))->normalize()->children()->empty(),
        );
    }

    public function testToString()
    {
        $this->assertSame(
            '<a href="http://example.com/"></a>'."\n",
            A::of(Url::of('http://example.com/'))
                ->normalize()
                ->asContent()
                ->toString(),
        );
    }
}
