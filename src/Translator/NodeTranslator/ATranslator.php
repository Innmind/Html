<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\Element\A;
use Innmind\Xml\{
    Translator\NodeTranslator,
    Translator\Translator,
    Node,
    Translator\NodeTranslator\Visitor\Attributes,
    Translator\NodeTranslator\Visitor\Children,
};
use Innmind\Url\Url;
use Innmind\Immutable\Maybe;

/**
 * @psalm-immutable
 */
final class ATranslator implements NodeTranslator
{
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
            ->filter(static fn(\DOMElement $node) => $node->tagName === 'a')
            ->flatMap(
                static fn(\DOMElement $node) => Attributes::of()($node)->flatMap(
                    static fn($attributes) => $attributes
                        ->find(static fn($attribute) => $attribute->name() === 'href')
                        ->flatMap(static fn($href) => Url::maybe($href->value()))
                        ->flatMap(
                            static fn($href) => Children::of($translate)($node)->map(
                                static fn($children) => new A(
                                    $href,
                                    $attributes,
                                    $children,
                                ),
                            ),
                        ),
                ),
            );
    }
}
