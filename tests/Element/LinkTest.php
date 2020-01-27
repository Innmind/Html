<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\{
    Element\Link,
    Exception\DomainException,
};
use Innmind\Xml\{
    Element\SelfClosingElement,
    Attribute,
};
use Innmind\Url\Url;
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            SelfClosingElement::class,
            $link = new Link(
                $href = Url::of('http://example.com'),
                'rel',
                Set::of(Attribute::class)
            )
        );
        $this->assertSame('link', $link->name());
        $this->assertSame($href, $link->href());
        $this->assertSame('rel', $link->relationship());
    }

    public function testWithoutAttributes()
    {
        $this->assertTrue(
            (new Link(
                Url::of('http://example.com'),
                'foo'
            ))->attributes()->empty(),
        );
    }

    public function testThrowWhenEmptyRelationship()
    {
        $this->expectException(DomainException::class);

        new Link(
            Url::of('http://example.com'),
            ''
        );
    }
}
