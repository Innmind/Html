<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\{
    Element\Link,
    Exception\InvalidArgumentException,
};
use Innmind\Xml\{
    Element\SelfClosingElement,
    Attribute,
};
use Innmind\Url\UrlInterface;
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            SelfClosingElement::class,
            $link = new Link(
                $href = $this->createMock(UrlInterface::class),
                'rel',
                $attributes = new Map('string', Attribute::class)
            )
        );
        $this->assertSame('link', $link->name());
        $this->assertSame($href, $link->href());
        $this->assertSame('rel', $link->relationship());
        $this->assertSame($attributes, $link->attributes());
    }

    public function testWithoutAttributes()
    {
        $this->assertFalse(
            (new Link(
                $this->createMock(UrlInterface::class),
                'foo'
            ))->hasAttributes()
        );
    }

    public function testThrowWhenEmptyRelationship()
    {
        $this->expectException(InvalidArgumentException::class);

        new Link(
            $this->createMock(UrlInterface::class),
            ''
        );
    }
}
