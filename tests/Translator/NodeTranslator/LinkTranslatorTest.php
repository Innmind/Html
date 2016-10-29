<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\LinkTranslator,
    Element\Link
};
use Innmind\Xml\Translator\{
    NodeTranslator,
    NodeTranslators,
    NodeTranslatorInterface
};

class LinkTranslatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslatorInterface::class,
            new LinkTranslator
        );
    }

    /**
     * @expectedException Innmind\Html\Exception\InvalidArgumentException
     */
    public function testThrowWhenNotExpectedElement()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<body></body>');

        (new LinkTranslator)->translate(
            $dom->childNodes->item(1),
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );
    }

    public function testTranslate()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<link href="/" rel="next" hreflang="fr"/>');

        $link = (new LinkTranslator)->translate(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );

        $this->assertInstanceOf(Link::class, $link);
        $this->assertSame('/', (string) $link->href());
        $this->assertSame('next', $link->relationship());
        $this->assertCount(3, $link->attributes());
        $this->assertSame('fr', $link->attributes()->get('hreflang')->value());
    }

    public function testTranslateWithoutRelationship()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<link href="/" hreflang="fr"/>');

        $link = (new LinkTranslator)->translate(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );

        $this->assertInstanceOf(Link::class, $link);
        $this->assertSame('/', (string) $link->href());
        $this->assertSame('related', $link->relationship());
        $this->assertCount(2, $link->attributes());
        $this->assertSame('fr', $link->attributes()->get('hreflang')->value());
    }

    /**
     * @expectedException Innmind\Html\Exception\MissingHrefAttributeException
     */
    public function testThrowWhenMissingHrefAttribute()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<link/>');

        (new LinkTranslator)->translate(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );
    }
}
