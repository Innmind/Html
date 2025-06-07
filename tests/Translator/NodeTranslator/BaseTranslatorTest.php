<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator,
    Element\Base,
};
use Innmind\Immutable\Predicate\Instance;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class BaseTranslatorTest extends TestCase
{
    public function testTranslate()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<base href="/" target="_blank"/>');

        $base = Translator::new()(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
        )->match(
            static fn($base) => $base,
            static fn() => null,
        );

        $this->assertInstanceOf(Base::class, $base);
        $this->assertSame('/', $base->href()->toString());
        $base = $base->normalize();
        $this->assertCount(2, $base->attributes());
        $this->assertSame('_blank', $base->attribute('target')->match(
            static fn($attribute) => $attribute->value(),
            static fn() => null,
        ));
    }

    public function testReturnNothingWhenMissingHrefAttribute()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<base/>');

        $result = Translator::new()(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
        )
            ->maybe()
            ->keep(Instance::of(Base::class));

        $this->assertNull($result->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }
}
