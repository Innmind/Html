<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator,
    Element\Link,
};
use Innmind\Immutable\Predicate\Instance;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class LinkTranslatorTest extends TestCase
{
    public function testTranslate()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<link href="/" rel="next" hreflang="fr"/>');

        $link = Translator::new()(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
        )->match(
            static fn($link) => $link,
            static fn() => null,
        );

        $this->assertInstanceOf(Link::class, $link);
        $this->assertSame('/', $link->href()->toString());
        $this->assertSame('next', $link->relationship());
        $link = $link->normalize();
        $this->assertCount(3, $link->attributes());
        $this->assertSame('fr', $link->attribute('hreflang')->match(
            static fn($attribute) => $attribute->value(),
            static fn() => null,
        ));
    }

    public function testTranslateWithoutRelationship()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<link href="/" hreflang="fr"/>');

        $link = Translator::new()(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
        )->match(
            static fn($link) => $link,
            static fn() => null,
        );

        $this->assertInstanceOf(Link::class, $link);
        $this->assertSame('/', $link->href()->toString());
        $this->assertSame('related', $link->relationship());
        $link = $link->normalize();
        $this->assertCount(3, $link->attributes());
        $this->assertSame('fr', $link->attribute('hreflang')->match(
            static fn($attribute) => $attribute->value(),
            static fn() => null,
        ));
        $this->assertSame('related', $link->attribute('rel')->match(
            static fn($attribute) => $attribute->value(),
            static fn() => null,
        ));
    }

    public function testReturnNothingWhenMissingHrefAttribute()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<link/>');

        $result = Translator::new()(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
        )
            ->maybe()
            ->keep(Instance::of(Link::class));

        $this->assertNull($result->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }
}
