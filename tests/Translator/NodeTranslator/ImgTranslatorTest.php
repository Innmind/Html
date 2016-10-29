<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\ImgTranslator,
    Element\Img
};
use Innmind\Xml\Translator\{
    NodeTranslator,
    NodeTranslators,
    NodeTranslatorInterface
};

class ImgTranslatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslatorInterface::class,
            new ImgTranslator
        );
    }

    /**
     * @expectedException Innmind\Html\Exception\InvalidArgumentException
     */
    public function testThrowWhenNotExpectedElement()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<body></body>');

        (new ImgTranslator)->translate(
            $dom->childNodes->item(1),
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );
    }

    public function testTranslate()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<img src="foo.png" alt="bar"/>');

        $img = (new ImgTranslator)->translate(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );

        $this->assertInstanceOf(Img::class, $img);
        $this->assertSame('foo.png', (string) $img->src());
        $this->assertCount(2, $img->attributes());
        $this->assertSame('bar', $img->attributes()->get('alt')->value());
    }

    /**
     * @expectedException Innmind\Html\Exception\MissingSrcAttributeException
     */
    public function testThrowWhenMissingHrefAttribute()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<img/>');

        (new ImgTranslator)->translate(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );
    }
}
