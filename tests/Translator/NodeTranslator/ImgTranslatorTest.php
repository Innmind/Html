<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator,
    Element\Img,
};
use Innmind\Immutable\Predicate\Instance;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ImgTranslatorTest extends TestCase
{
    public function testTranslate()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<img src="foo.png" alt="bar"/>');

        $img = Translator::new()(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
        )->match(
            static fn($img) => $img,
            static fn() => null,
        );

        $this->assertInstanceOf(Img::class, $img);
        $this->assertSame('foo.png', $img->src()->toString());
        $img = $img->normalize();
        $this->assertCount(2, $img->attributes());
        $this->assertSame('bar', $img->attribute('alt')->match(
            static fn($attribute) => $attribute->value(),
            static fn() => null,
        ));
    }

    public function testReturnNothingWhenMissingHrefAttribute()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<img/>');

        $result = Translator::new()(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
        )
            ->maybe()
            ->keep(Instance::of(Img::class));

        $this->assertNull($result->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }
}
