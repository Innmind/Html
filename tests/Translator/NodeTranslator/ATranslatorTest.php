<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\ATranslator,
    Element\A,
    Exception\InvalidArgumentException,
    Exception\MissingHrefAttribute,
};
use Innmind\Xml\Translator\{
    Translator,
    NodeTranslators,
    NodeTranslator,
};
use PHPUnit\Framework\TestCase;

class ATranslatorTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslator::class,
            new ATranslator
        );
    }

    public function testThrowWhenNotExpectedElement()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<body></body>');

        $this->expectException(InvalidArgumentException::class);

        (new ATranslator)(
            $dom->childNodes->item(1),
            new Translator(
                NodeTranslators::defaults()
            )
        );
    }

    public function testTranslate()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<a href="/" class="whatever">foo</a>');

        $a = (new ATranslator)(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            new Translator(
                NodeTranslators::defaults()
            )
        );

        $this->assertInstanceOf(A::class, $a);
        $this->assertSame('/', $a->href()->toString());
        $this->assertCount(2, $a->attributes());
        $this->assertSame('whatever', $a->attributes()->get('class')->value());
        $this->assertCount(1, $a->children());
    }

    public function testThrowWhenMissingHrefAttribute()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<a class="whatever">foo</a>');

        $this->expectException(MissingHrefAttribute::class);

        (new ATranslator)(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            new Translator(
                NodeTranslators::defaults()
            )
        );
    }
}
