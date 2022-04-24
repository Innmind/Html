<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\Img;
use Innmind\Xml\{
    Element,
    Attribute,
};
use Innmind\Url\Url;
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class ImgTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Element::class,
            $img = new Img(
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
            (new Img(Url::of('http://example.com')))->attributes()->empty(),
        );
    }
}
