<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\Element\Script;
use Innmind\Xml\{
    Translator\NodeTranslator,
    Translator\Translator,
    Node,
    Translator\NodeTranslator\Visitor\Attributes,
    Node\Text,
};
use Innmind\Immutable\Maybe;

/**
 * @psalm-immutable
 */
final class ScriptTranslator implements NodeTranslator
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
            ->filter(static fn(\DOMElement $node) => $node->tagName === 'script')
            ->flatMap(static fn(\DOMElement $node) => Attributes::of()($node)->map(
                static fn($attributes) => new Script(
                    Text::of(
                        Maybe::of($node->firstChild)
                            ->flatMap(static fn($node) => $translate($node))
                            ->map(static fn($node) => $node->content())
                            ->match(
                                static fn($content) => $content,
                                static fn() => '',
                            ),
                    ),
                    $attributes,
                ),
            ));
    }
}
