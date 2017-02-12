<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\ElementTranslator,
    Exception\ExceptionInterface
};
use Innmind\Xml\{
    Element\SelfClosingElement,
    Translator\NodeTranslator,
    Translator\NodeTranslators,
    Translator\NodeTranslator\ElementTranslator as GenericTranslator,
    Translator\NodeTranslatorInterface,
    NodeInterface
};
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class ElementTranslatorTest extends TestCase
{
    private $translator;
    private $bar;
    private $baz;

    public function setUp()
    {
        $this->translator = new ElementTranslator(
            new GenericTranslator,
            (new Map('string', NodeTranslatorInterface::class))
                ->put(
                    'bar',
                    $this->bar = $this->createMock(NodeTranslatorInterface::class)
                )
                ->put(
                    'baz',
                    $this->baz = $this->createMock(NodeTranslatorInterface::class)
                )
        );
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslatorInterface::class,
            $this->translator
        );
    }

    /**
     * @expectedException Innmind\Html\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidTranslators()
    {
        new ElementTranslator(
            new GenericTranslator,
            new Map('int', NodeTranslatorInterface::class)
        );
    }

    /**
     * @expectedException Innmind\Html\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidNode()
    {
        (new ElementTranslator(
            new GenericTranslator,
            new Map('string', NodeTranslatorInterface::class)
        ))->translate(
            new \DOMNode,
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );
    }

    public function testTranslateToGenericWhenNoSubTranslatorFound()
    {
        $dom = new \DOMDocument;
        $dom->loadXML('<foo/>');

        $node = $this->translator->translate(
            $dom->childNodes->item(0),
            new NodeTranslator(
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
            ->method('translate')
            ->with($dom->childNodes->item(0))
            ->will(
                $this->throwException(
                    new class extends \Exception implements ExceptionInterface{}
                )
            );

        $node = $this->translator->translate(
            $dom->childNodes->item(0),
            new NodeTranslator(
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
            ->method('translate')
            ->with($dom->childNodes->item(0))
            ->willReturn($expected = $this->createMock(NodeInterface::class));

        $node = $this->translator->translate(
            $dom->childNodes->item(0),
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );

        $this->assertSame($expected, $node);
    }
}
