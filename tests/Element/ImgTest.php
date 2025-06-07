<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\Img;
use Innmind\Xml\Element;
use Innmind\Url\Url;
use Innmind\Immutable\Sequence;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ImgTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Element\Custom::class,
            $img = Img::of(
                $src = Url::of('http://example.com'),
                Sequence::of(),
            ),
        );
        $this->assertSame('img', $img->normalize()->name()->toString());
        $this->assertSame($src, $img->src());
    }

    public function testWithoutAttributes()
    {
        $this->assertFalse(
            Img::of(Url::of('http://example.com'))->normalize()->attributes()->empty(),
        );
    }

    public function testToString()
    {
        $this->assertSame(
            '<img src="http://example.com/"/>'."\n",
            Img::of(Url::of('http://example.com/'))
                ->normalize()
                ->asContent()
                ->toString(),
        );
    }
}
