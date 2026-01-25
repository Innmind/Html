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
        $dom = \Dom\HTMLDocument::createFromString(
            '<link href="/" rel="next" hreflang="fr"/>',
            \LIBXML_HTML_NOIMPLIED | \LIBXML_NOERROR,
        );

        $link = Translator::new()(
            $dom->childNodes->item(0),
        )->match(
            static fn($link) => $link,
            static fn() => null,
        );

        $this->assertInstanceOf(Link::class, $link);
        $this->assertSame('/', $link->href()->toString());
        $this->assertSame('next', $link->relationship());
        $link = $link->normalize();
        $this->assertSame(3, $link->attributes()->size());
        $this->assertSame('fr', $link->attribute('hreflang')->match(
            static fn($attribute) => $attribute->value(),
            static fn() => null,
        ));
    }

    public function testTranslateWithoutRelationship()
    {
        $dom = \Dom\HTMLDocument::createFromString(
            '<link href="/" hreflang="fr"/>',
            \LIBXML_HTML_NOIMPLIED | \LIBXML_NOERROR,
        );

        $link = Translator::new()(
            $dom->childNodes->item(0),
        )->match(
            static fn($link) => $link,
            static fn() => null,
        );

        $this->assertInstanceOf(Link::class, $link);
        $this->assertSame('/', $link->href()->toString());
        $this->assertSame('related', $link->relationship());
        $link = $link->normalize();
        $this->assertSame(3, $link->attributes()->size());
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
        $dom = \Dom\HTMLDocument::createFromString(
            '<link/>',
            \LIBXML_HTML_NOIMPLIED | \LIBXML_NOERROR,
        );

        $result = Translator::new()(
            $dom->childNodes->item(0),
        )
            ->maybe()
            ->keep(Instance::of(Link::class));

        $this->assertNull($result->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }
}
