<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\LinkTranslator,
    Element\Link,
};
use Innmind\Xml\Translator\{
    Translator,
    NodeTranslators,
    NodeTranslator,
};
use PHPUnit\Framework\TestCase;

class LinkTranslatorTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslator::class,
            new LinkTranslator,
        );
    }

    public function testReturnNothingWhenNotExpectedElement()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<body></body>');

        $result = (new LinkTranslator)(
            $dom->childNodes->item(1),
            Translator::of(
                NodeTranslators::defaults(),
            )
        );

        $this->assertNull($result->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }

    public function testTranslate()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<link href="/" rel="next" hreflang="fr"/>');

        $link = (new LinkTranslator)(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            Translator::of(
                NodeTranslators::defaults(),
            )
        )->match(
            static fn($link) => $link,
            static fn() => null,
        );

        $this->assertInstanceOf(Link::class, $link);
        $this->assertSame('/', $link->href()->toString());
        $this->assertSame('next', $link->relationship());
        $this->assertCount(3, $link->attributes());
        $this->assertSame('fr', $link->attributes()->get('hreflang')->match(
            static fn($attribute) => $attribute->value(),
            static fn() => null,
        ));
    }

    public function testTranslateWithoutRelationship()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<link href="/" hreflang="fr"/>');

        $link = (new LinkTranslator)(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            Translator::of(
                NodeTranslators::defaults(),
            )
        )->match(
            static fn($link) => $link,
            static fn() => null,
        );

        $this->assertInstanceOf(Link::class, $link);
        $this->assertSame('/', $link->href()->toString());
        $this->assertSame('related', $link->relationship());
        $this->assertCount(2, $link->attributes());
        $this->assertSame('fr', $link->attributes()->get('hreflang')->match(
            static fn($attribute) => $attribute->value(),
            static fn() => null,
        ));
    }

    public function testReturnNothingWhenMissingHrefAttribute()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<link/>');

        $result = (new LinkTranslator)(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            Translator::of(
                NodeTranslators::defaults(),
            )
        );

        $this->assertNull($result->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }
}
