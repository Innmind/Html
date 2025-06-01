<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\Base;
use Innmind\Xml\Element;
use Innmind\Url\Url;
use Innmind\Immutable\Set;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Element::class,
            $base = Base::of(
                $href = Url::of('http://example.com'),
                Set::of(),
            ),
        );
        $this->assertSame('base', $base->name());
        $this->assertSame($href, $base->href());
    }

    public function testWithoutAttributes()
    {
        $this->assertTrue(
            Base::of(Url::of('http://example.com'))->attributes()->empty(),
        );
    }

    public function testToString()
    {
        $this->assertSame(
            '<base href="http://example.com/"/>',
            Base::of(Url::of('http://example.com/'))->toString(),
        );
    }
}
