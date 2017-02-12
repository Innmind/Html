<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\Img;
use Innmind\Xml\{
    Element\SelfClosingElement,
    AttributeInterface
};
use Innmind\Url\UrlInterface;
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class ImgTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            SelfClosingElement::class,
            $img = new Img(
                $src = $this->createMock(UrlInterface::class),
                $attributes = new Map('string', AttributeInterface::class)
            )
        );
        $this->assertSame('img', $img->name());
        $this->assertSame($src, $img->src());
        $this->assertSame($attributes, $img->attributes());
    }

    public function testWithoutAttributes()
    {
        $this->assertFalse(
            (new Img($this->createMock(UrlInterface::class)))->hasAttributes()
        );
    }
}
