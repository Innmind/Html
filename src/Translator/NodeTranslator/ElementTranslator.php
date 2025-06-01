<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Xml\{
    Translator\NodeTranslator,
    Translator\Translator,
    Translator\NodeTranslator\ElementTranslator as GenericTranslator,
};
use Innmind\Immutable\{
    Map,
    Maybe,
};

/**
 * @psalm-immutable
 */
final class ElementTranslator implements NodeTranslator
{
    private GenericTranslator $genericTranslator;
    /** @var Map<string, NodeTranslator> */
    private Map $translators;

    /**
     * @param Map<string, NodeTranslator> $translators
     */
    private function __construct(
        GenericTranslator $genericTranslator,
        Map $translators,
    ) {
        $this->genericTranslator = $genericTranslator;
        $this->translators = $translators;
    }

    #[\Override]
    public function __invoke(
        \DOMNode $node,
        Translator $translate,
    ): Maybe {
        /** @psalm-suppress ArgumentTypeCoercion */
        return Maybe::just($node)
            ->filter(static fn($node) => $node instanceof \DOMElement)
            ->flatMap(fn(\DOMElement $node) => $this->translators->get($node->tagName))
            ->flatMap(static fn($translator) => $translator($node, $translate))
            ->otherwise(fn() => ($this->genericTranslator)($node, $translate));
    }

    /**
     * @psalm-pure
     *
     * @param Map<string, NodeTranslator> $translators
     */
    public static function of(
        GenericTranslator $genericTranslator,
        Map $translators,
    ): self {
        return new self($genericTranslator, $translators);
    }
}
