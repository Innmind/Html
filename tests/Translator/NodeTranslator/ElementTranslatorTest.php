<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\ElementTranslator,
    Exception\Exception,
    Exception\InvalidArgumentException,
};
use Innmind\Xml\{
    Element\SelfClosingElement,
    Translator\Translator,
    Translator\NodeTranslators,
    Translator\NodeTranslator\ElementTranslator as GenericTranslator,
    Translator\NodeTranslator,
    Node,
};
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class ElementTranslatorTest extends TestCase
{
    private $translate;
    private $bar;
    private $baz;

    public function setUp()
    {
        $this->translate = new ElementTranslator(
            new GenericTranslator,
            Map::of('string', NodeTranslator::class)
                ('bar', $this->bar = $this->createMock(NodeTranslator::class))
                ('baz', $this->baz = $this->createMock(NodeTranslator::class))
        );
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslator::class,
            $this->translate
        );
    }

    public function testThrowWhenInvalidTranslators()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 2 must be of type MapInterface<string, Innmind\Xml\Translator\NodeTranslator>');

        new ElementTranslator(
            new GenericTranslator,
            new Map('int', NodeTranslator::class)
        );
    }

    public function testThrowWhenInvalidNode()
    {
        $this->expectException(InvalidArgumentException::class);

        (new ElementTranslator(
            new GenericTranslator,
            new Map('string', NodeTranslator::class)
        ))(
            new \DOMNode,
            new Translator(
                NodeTranslators::defaults()
            )
        );
    }

    public function testTranslateToGenericWhenNoSubTranslatorFound()
    {
        $dom = new \DOMDocument;
        $dom->loadXML('<foo/>');

        $node = ($this->translate)(
            $dom->childNodes->item(0),
            new Translator(
                NodeTranslators::defaults()
            )
        );

        $this->assertInstanceOf(SelfClosingElement::class, $node);
    }

    public function testTranslateToGenericWhenSubTranslatorThrowsAnException()
    {
        $dom = new \DOMDocument;
        $dom->loadXML('<bar/>');

        $this->bar
            ->expects($this->once())
            ->method('__invoke')
            ->with($dom->childNodes->item(0))
            ->will(
                $this->throwException(
                    new class extends \Exception implements Exception {}
                )
            );

        $node = ($this->translate)(
            $dom->childNodes->item(0),
            new Translator(
                NodeTranslators::defaults()
            )
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
            ->willReturn($expected = $this->createMock(Node::class));

        $node = ($this->translate)(
            $dom->childNodes->item(0),
            new Translator(
                NodeTranslators::defaults()
            )
        );

        $this->assertSame($expected, $node);
    }
}
