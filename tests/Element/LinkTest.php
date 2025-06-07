<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\Link;
use Innmind\Xml\Element;
use Innmind\Url\Url;
use Innmind\Immutable\Sequence;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Element\Custom::class,
            $link = Link::of(
                $href = Url::of('http://example.com'),
                'rel',
                Sequence::of(),
            ),
        );
        $this->assertSame('link', $link->normalize()->name()->toString());
        $this->assertSame($href, $link->href());
        $this->assertSame('rel', $link->relationship());
    }

    public function testWithoutAttributes()
    {
        $this->assertFalse(
            Link::of(
                Url::of('http://example.com'),
                'foo',
            )->normalize()->attributes()->empty(),
        );
    }

    public function testToString()
    {
        $this->assertSame(
            '<link rel="foo" href="http://example.com/"/>'."\n",
            Link::of(Url::of('http://example.com'), 'foo')
                ->normalize()
                ->asContent()
                ->toString(),
        );
    }
}
