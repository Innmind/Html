<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\Exception\{
    InvalidArgumentException,
    Exception,
};
use Innmind\Xml\{
    Translator\NodeTranslator,
    Translator\Translator,
    Translator\NodeTranslator\ElementTranslator as GenericTranslator,
    Node,
};
use Innmind\Immutable\{
    MapInterface,
    Exception\ElementNotFoundException,
};

final class ElementTranslator implements NodeTranslator
{
    private GenericTranslator $genericTranslator;
    private MapInterface $translators;

    public function __construct(
        GenericTranslator $genericTranslator,
        MapInterface $translators
    ) {
        if (
            (string) $translators->keyType() !== 'string' ||
            (string) $translators->valueType() !== NodeTranslator::class
        ) {
            throw new \TypeError(sprintf(
                'Argument 2 must be of type MapInterface<string, %s>',
                NodeTranslator::class
            ));
        }

        $this->genericTranslator = $genericTranslator;
        $this->translators = $translators;
    }

    public function __invoke(
        \DOMNode $node,
        Translator $translate
    ): Node {
        if (!$node instanceof \DOMElement) {
            throw new InvalidArgumentException;
        }

        try {
            return $this
                ->translators
                ->get($node->tagName)($node, $translate);
        } catch (ElementNotFoundException $e) {
            //pass
        } catch (Exception $e) {
            //pass
        }

        return ($this->genericTranslator)($node, $translate);
    }
}
