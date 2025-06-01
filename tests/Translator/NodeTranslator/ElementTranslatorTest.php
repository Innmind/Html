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
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ElementTranslatorTest extends TestCase
{
    private $translate;
    private $expected;

    public function setUp(): void
    {
        $this->expected = Node\Text::of('');
        $this->translate = ElementTranslator::of(
            GenericTranslator::of(),
            Map::of(
                [
                    'bar',
                    new class implements NodeTranslator {
                        public function __invoke(\DOMNode $node, Translator $translate): Maybe
                        {
                            return Maybe::nothing();
                        }
                    },
                ],
                [
                    'baz',
                    new class($this->expected) implements NodeTranslator {
                        public function __construct(
                            private $expected,
                        ) {
                        }

                        public function __invoke(\DOMNode $node, Translator $translate): Maybe
                        {
                            if ($node instanceof \DOMElement && $node->nodeName === 'baz') {
                                return Maybe::just($this->expected);
                            }

                            return Maybe::nothing();
                        }
                    },
                ],
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

        $node = ($this->translate)(
            $dom->childNodes->item(0),
            Translator::of(
                NodeTranslators::defaults(),
            )
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertSame($this->expected, $node);
    }
}
