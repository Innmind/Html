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
    Map,
    Exception\ElementNotFound,
};

final class ElementTranslator implements NodeTranslator
{
    private GenericTranslator $genericTranslator;
    /** @var Map<string, NodeTranslator> */
    private Map $translators;

    /**
     * @param Map<string, NodeTranslator> $translators
     */
    public function __construct(
        GenericTranslator $genericTranslator,
        Map $translators
    ) {
        if (
            (string) $translators->keyType() !== 'string' ||
            (string) $translators->valueType() !== NodeTranslator::class
        ) {
            throw new \TypeError(sprintf(
                'Argument 2 must be of type Map<string, %s>',
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
        } catch (ElementNotFound $e) {
            //pass
        } catch (Exception $e) {
            //pass
        }

        return ($this->genericTranslator)($node, $translate);
    }
}
