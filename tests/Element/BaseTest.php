<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\Base;
use Innmind\Xml\Element;
use Innmind\Url\Url;
use Innmind\Immutable\Sequence;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Element\Custom::class,
            $base = Base::of(
                $href = Url::of('http://example.com'),
                Sequence::of(),
            ),
        );
        $this->assertSame('base', $base->normalize()->name()->toString());
        $this->assertSame($href, $base->href());
    }

    public function testWithoutAttributes()
    {
        $this->assertFalse(
            Base::of(Url::of('http://example.com'))->normalize()->attributes()->empty(),
        );
    }

    public function testToString()
    {
        $this->assertSame(
            '<base href="http://example.com/"/>'."\n",
            Base::of(Url::of('http://example.com/'))
                ->normalize()
                ->asContent()
                ->toString(),
        );
    }
}
