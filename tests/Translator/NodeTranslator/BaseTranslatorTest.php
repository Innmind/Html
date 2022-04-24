<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\BaseTranslator,
    Element\Base,
};
use Innmind\Xml\Translator\{
    Translator,
    NodeTranslators,
    NodeTranslator,
};
use PHPUnit\Framework\TestCase;

class BaseTranslatorTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslator::class,
            new BaseTranslator,
        );
    }

    public function testReturnNothingWhenNotExpectedElement()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<body></body>');

        $result = (new BaseTranslator)(
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
        $dom->loadHTML('<base href="/" target="_blank"/>');

        $base = (new BaseTranslator)(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            Translator::of(
                NodeTranslators::defaults(),
            )
        )->match(
            static fn($base) => $base,
            static fn() => null,
        );

        $this->assertInstanceOf(Base::class, $base);
        $this->assertSame('/', $base->href()->toString());
        $this->assertCount(2, $base->attributes());
        $this->assertSame('_blank', $base->attributes()->get('target')->match(
            static fn($attribute) => $attribute->value(),
            static fn() => null,
        ));
    }

    public function testReturnNothingWhenMissingHrefAttribute()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<base/>');

        $result = (new BaseTranslator)(
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
