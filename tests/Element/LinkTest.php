<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\Link;
use Innmind\Xml\Element;
use Innmind\Url\Url;
use Innmind\Immutable\Set;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Element::class,
            $link = Link::of(
                $href = Url::of('http://example.com'),
                'rel',
                Set::of(),
            ),
        );
        $this->assertSame('link', $link->name());
        $this->assertSame($href, $link->href());
        $this->assertSame('rel', $link->relationship());
    }

    public function testWithoutAttributes()
    {
        $this->assertTrue(
            Link::of(
                Url::of('http://example.com'),
                'foo',
            )->attributes()->empty(),
        );
    }

    public function testToString()
    {
        $this->assertSame(
            '<link rel="foo" href="http://example.com/"/>',
            Link::of(Url::of('http://example.com'), 'foo')->toString(),
        );
    }
}
