<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\Translator\NodeTranslator\ElementTranslator;
use Innmind\Xml\{
    Element\SelfClosingElement,
    Translator\Translator,
    Translator\NodeTranslators,
    Translator\NodeTranslator\ElementTranslator as GenericTranslator,
    Translator\NodeTranslator,
    Node,
};
use Innmind\Immutable\{
    Map,
    Maybe,
};
use PHPUnit\Framework\TestCase;

class ElementTranslatorTest extends TestCase
{
    private $translate;
    private $bar;
    private $baz;

    public function setUp(): void
    {
        $this->translate = ElementTranslator::of(
            GenericTranslator::of(),
            Map::of(
                ['bar', $this->bar = $this->createMock(NodeTranslator::class)],
                ['baz', $this->baz = $this->createMock(NodeTranslator::class)],
            ),
        );
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslator::class,
            $this->translate,
        );
    }

    public function testReturnNothingWhenInvalidNode()
    {
        $result = ElementTranslator::of(
            GenericTranslator::of(),
            Map::of(),
        )(
            new \DOMNode,
            Translator::of(
                NodeTranslators::defaults(),
            ),
        );

        $this->assertNull($result->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }

    public function testTranslateToGenericWhenNoSubTranslatorFound()
    {
        $dom = new \DOMDocument;
        $dom->loadXML('<foo/>');

        $node = ($this->translate)(
            $dom->childNodes->item(0),
            Translator::of(
                NodeTranslators::defaults(),
            )
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertInstanceOf(SelfClosingElement::class, $node);
    }

    public function testTranslateToGenericWhenSubTranslatorReturnsNothing()
    {
        $dom = new \DOMDocument;
        $dom->loadXML('<bar/>');

        $this->bar
            ->expects($this->once())
            ->method('__invoke')
            ->with($dom->childNodes->item(0))
            ->willReturn(Maybe::nothing());

        $node = ($this->translate)(
            $dom->childNodes->item(0),
            Translator::of(
                NodeTranslators::defaults(),
            )
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertInstanceOf(SelfClosingElement::class, $node);
    }

    public function testTranslateViaSubTranslator()
    {
        $dom = new \DOMDocument;
        $dom->loadXML('<baz/>');

        $this->baz
            ->expects($this->once())
            ->method('__invoke')
            ->with($dom->childNodes->item(0))
            ->willReturn(Maybe::just($expected = $this->createMock(Node::class)));

        $node = ($this->translate)(
            $dom->childNodes->item(0),
            Translator::of(
                NodeTranslators::defaults(),
            )
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertSame($expected, $node);
    }
}
