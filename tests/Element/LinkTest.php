<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\Link;
use Innmind\Xml\{
    Element\SelfClosingElement,
    AttributeInterface
};
use Innmind\Url\UrlInterface;
use Innmind\Immutable\Map;

class LinkTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            SelfClosingElement::class,
            $link = new Link(
                $href = $this->createMock(UrlInterface::class),
                'rel',
                $attributes = new Map('string', AttributeInterface::class)
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

    /**
     * @expectedException Innmind\Html\Exception\InvalidArgumentException
     */
    public function testThrowWhenEmptyRelationship()
    {
        new Link(
            $this->createMock(UrlInterface::class),
            ''
        );
    }
}
