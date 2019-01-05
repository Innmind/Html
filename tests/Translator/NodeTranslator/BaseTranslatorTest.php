<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\BaseTranslator,
    Element\Base,
    Exception\InvalidArgumentException,
    Exception\MissingHrefAttribute,
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
            new BaseTranslator
        );
    }

    public function testThrowWhenNotExpectedElement()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<body></body>');

        $this->expectException(InvalidArgumentException::class);

        (new BaseTranslator)(
            $dom->childNodes->item(1),
            new Translator(
                NodeTranslators::defaults()
            )
        );
    }

    public function testTranslate()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<base href="/" target="_blank"/>');

        $base = (new BaseTranslator)(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            new Translator(
                NodeTranslators::defaults()
            )
        );

        $this->assertInstanceOf(Base::class, $base);
        $this->assertSame('/', (string) $base->href());
        $this->assertCount(2, $base->attributes());
        $this->assertSame('_blank', $base->attributes()->get('target')->value());
    }

    public function testThrowWhenMissingHrefAttribute()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<base/>');

        $this->expectException(MissingHrefAttribute::class);

        (new BaseTranslator)(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            new Translator(
                NodeTranslators::defaults()
            )
        );
    }
}
