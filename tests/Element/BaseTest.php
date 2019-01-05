<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\Base;
use Innmind\Xml\{
    Element\SelfClosingElement,
    Attribute,
};
use Innmind\Url\UrlInterface;
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            SelfClosingElement::class,
            $base = new Base(
                $href = $this->createMock(UrlInterface::class),
                $attributes = new Map('string', Attribute::class)
            )
        );
        $this->assertSame('base', $base->name());
        $this->assertSame($href, $base->href());
        $this->assertSame($attributes, $base->attributes());
    }

    public function testWithoutAttributes()
    {
        $this->assertFalse(
            (new Base($this->createMock(UrlInterface::class)))->hasAttributes()
        );
    }
}
