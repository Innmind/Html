<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\ATranslator,
    Element\A
};
use Innmind\Xml\Translator\{
    NodeTranslator,
    NodeTranslators,
    NodeTranslatorInterface
};
use PHPUnit\Framework\TestCase;

class ATranslatorTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslatorInterface::class,
            new ATranslator
        );
    }

    /**
     * @expectedException Innmind\Html\Exception\InvalidArgumentException
     */
    public function testThrowWhenNotExpectedElement()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<body></body>');

        (new ATranslator)->translate(
            $dom->childNodes->item(1),
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );
    }

    public function testTranslate()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<a href="/" class="whatever">foo</a>');

        $a = (new ATranslator)->translate(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );

        $this->assertInstanceOf(A::class, $a);
        $this->assertSame('/', (string) $a->href());
        $this->assertCount(2, $a->attributes());
        $this->assertSame('whatever', $a->attributes()->get('class')->value());
        $this->assertCount(1, $a->children());
    }

    /**
     * @expectedException Innmind\Html\Exception\MissingHrefAttributeException
     */
    public function testThrowWhenMissingHrefAttribute()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<a class="whatever">foo</a>');

        (new ATranslator)->translate(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );
    }
}
