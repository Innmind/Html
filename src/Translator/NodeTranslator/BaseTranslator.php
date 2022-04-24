<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\Element\Base;
use Innmind\Xml\{
    Translator\NodeTranslator,
    Translator\Translator,
    Node,
    Translator\NodeTranslator\Visitor\Attributes,
};
use Innmind\Url\Url;
use Innmind\Immutable\Maybe;

/**
 * @psalm-immutable
 */
final class BaseTranslator implements NodeTranslator
{
    private function __construct()
    {
    }

    public function __invoke(
        \DOMNode $node,
        Translator $translate,
    ): Maybe {
        /**
         * @psalm-suppress ArgumentTypeCoercion
         * @var Maybe<Node>
         */
        return Maybe::just($node)
            ->filter(static fn($node) => $node instanceof \DOMElement)
            ->filter(static fn(\DOMElement $node) => $node->tagName === 'base')
            ->flatMap(
                static fn(\DOMElement $node) => Attributes::of()($node)->flatMap(
                    static fn($attributes) => $attributes
                        ->find(static fn($attribute) => $attribute->name() === 'href')
                        ->flatMap(static fn($href) => Url::maybe($href->value()))
                        ->map(static fn($href) => Base::of(
                            $href,
                            $attributes,
                        )),
                ),
            );
    }

    /**
     * @psalm-pure
     */
    public static function of(): self
    {
        return new self;
    }
}
