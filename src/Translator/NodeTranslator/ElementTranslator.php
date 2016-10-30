<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\Exception\{
    InvalidArgumentException,
    ExceptionInterface
};
use Innmind\Xml\{
    Translator\NodeTranslatorInterface,
    Translator\NodeTranslator,
    Translator\NodeTranslator\ElementTranslator as GenericTranslator,
    NodeInterface
};
use Innmind\Immutable\{
    MapInterface,
    Exception\ElementNotFoundException
};

final class ElementTranslator implements NodeTranslatorInterface
{
    private $genericTranslator;
    private $translators;

    public function __construct(
        GenericTranslator $genericTranslator,
        MapInterface $translators
    ) {
        if (
            (string) $translators->keyType() !== 'string' ||
            (string) $translators->valueType() !== NodeTranslatorInterface::class
        ) {
            throw new InvalidArgumentException;
        }

        $this->genericTranslator = $genericTranslator;
        $this->translators = $translators;
    }

    public function translate(
        \DOMNode $node,
        NodeTranslator $translator
    ): NodeInterface {
        if (!$node instanceof \DOMElement) {
            throw new InvalidArgumentException;
        }

        try {
            return $this
                ->translators
                ->get($node->tagName)
                ->translate($node, $translator);
        } catch (ElementNotFoundException $e) {
            //pass
        } catch (ExceptionInterface $e) {
            //pass
        }

        return $this->genericTranslator->translate($node, $translator);
    }
}
