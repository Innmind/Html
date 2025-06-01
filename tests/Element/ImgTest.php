<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\Img;
use Innmind\Xml\Element;
use Innmind\Url\Url;
use Innmind\Immutable\Set;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ImgTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Element::class,
            $img = Img::of(
                $src = Url::of('http://example.com'),
                Set::of(),
            ),
        );
        $this->assertSame('img', $img->name());
        $this->assertSame($src, $img->src());
    }

    public function testWithoutAttributes()
    {
        $this->assertTrue(
            Img::of(Url::of('http://example.com'))->attributes()->empty(),
        );
    }

    public function testToString()
    {
        $this->assertSame(
            '<img src="http://example.com/"/>',
            Img::of(Url::of('http://example.com/'))->toString(),
        );
    }
}
