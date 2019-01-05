<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\ElementTranslator,
    Exception\ExceptionInterface
};
use Innmind\Xml\{
    Element\SelfClosingElement,
    Translator\Translator,
    Translator\NodeTranslators,
    Translator\NodeTranslator\ElementTranslator as GenericTranslator,
    Translator\NodeTranslator,
    Node
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
            (new Map('string', NodeTranslator::class))
                ->put(
                    'bar',
                    $this->bar = $this->createMock(NodeTranslator::class)
                )
                ->put(
                    'baz',
                    $this->baz = $this->createMock(NodeTranslator::class)
                )
        );
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslator::class,
            $this->translate
        );
    }

    /**
     * @expectedException Innmind\Html\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidTranslators()
    {
        new ElementTranslator(
            new GenericTranslator,
            new Map('int', NodeTranslator::class)
        );
    }

    /**
     * @expectedException Innmind\Html\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidNode()
    {
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
                    new class extends \Exception implements ExceptionInterface{}
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
