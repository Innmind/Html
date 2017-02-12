<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\A;
use Innmind\Xml\{
    Element\Element,
    AttributeInterface,
    NodeInterface
};
use Innmind\Url\UrlInterface;
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class ATest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Element::class,
            $a = new A(
                $href = $this->createMock(UrlInterface::class),
                $attributes = new Map('string', AttributeInterface::class),
                $children = new Map('int', NodeInterface::class)
            )
        );
        $this->assertSame('a', $a->name());
        $this->assertSame($href, $a->href());
        $this->assertSame($attributes, $a->attributes());
        $this->assertSame($children, $a->children());
    }

    public function testWithoutAttributes()
    {
        $this->assertFalse(
            (new A($this->createMock(UrlInterface::class)))->hasAttributes()
        );
    }

    public function testWithoutChildren()
    {
        $this->assertFalse(
            (new A($this->createMock(UrlInterface::class)))->hasChildren()
        );
    }
}
