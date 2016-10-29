<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\BaseTranslator,
    Element\Base
};
use Innmind\Xml\Translator\{
    NodeTranslator,
    NodeTranslators,
    NodeTranslatorInterface
};

class BaseTranslatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslatorInterface::class,
            new BaseTranslator
        );
    }

    /**
     * @expectedException Innmind\Html\Exception\InvalidArgumentException
     */
    public function testThrowWhenNotExpectedElement()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<body></body>');

        (new BaseTranslator)->translate(
            $dom->childNodes->item(1),
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );
    }

    public function testTranslate()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<base href="/" target="_blank"/>');

        $base = (new BaseTranslator)->translate(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );

        $this->assertInstanceOf(Base::class, $base);
        $this->assertSame('/', (string) $base->href());
        $this->assertCount(2, $base->attributes());
        $this->assertSame('_blank', $base->attributes()->get('target')->value());
    }

    /**
     * @expectedException Innmind\Html\Exception\MissingHrefAttributeException
     */
    public function testThrowWhenMissingHrefAttribute()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<base/>');

        (new BaseTranslator)->translate(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );
    }
}
