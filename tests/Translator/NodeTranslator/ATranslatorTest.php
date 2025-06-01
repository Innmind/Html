<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\ATranslator,
    Element\A,
};
use Innmind\Xml\Translator\{
    Translator,
    NodeTranslators,
    NodeTranslator,
};
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ATranslatorTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslator::class,
            ATranslator::of(),
        );
    }

    public function testReturnNothingWhenNotExpectedElement()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<body></body>');

        $result = ATranslator::of()(
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
        $dom->loadHTML('<a href="/" class="whatever">foo</a>');

        $a = ATranslator::of()(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            Translator::of(
                NodeTranslators::defaults(),
            )
        )->match(
            static fn($a) => $a,
            static fn() => null,
        );

        $this->assertInstanceOf(A::class, $a);
        $this->assertSame('/', $a->href()->toString());
        $this->assertCount(2, $a->attributes());
        $this->assertSame('whatever', $a->attributes()->get('class')->match(
            static fn($attribute) => $attribute->value(),
            static fn() => null,
        ));
        $this->assertCount(1, $a->children());
    }

    public function testReturnNothingWhenMissingHrefAttribute()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<a class="whatever">foo</a>');

        $result = ATranslator::of()(
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
