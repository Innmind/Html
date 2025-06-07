<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator,
    Element\A,
};
use Innmind\Immutable\Predicate\Instance;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ATranslatorTest extends TestCase
{
    public function testTranslate()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<a href="/" class="whatever">foo</a>');

        $a = Translator::new()(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
        )->match(
            static fn($a) => $a,
            static fn() => null,
        );

        $this->assertInstanceOf(A::class, $a);
        $this->assertSame('/', $a->href()->toString());
        $a = $a->normalize();
        $this->assertCount(2, $a->attributes());
        $this->assertSame('whatever', $a->attribute('class')->match(
            static fn($attribute) => $attribute->value(),
            static fn() => null,
        ));
        $this->assertCount(1, $a->children());
    }

    public function testReturnNothingWhenMissingHrefAttribute()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<a class="whatever">foo</a>');

        $result = Translator::new()(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
        )
            ->maybe()
            ->keep(Instance::of(A::class));

        $this->assertNull($result->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }
}
