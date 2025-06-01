<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\Element\Link;
use Innmind\Xml\{
    Translator\NodeTranslator,
    Translator\Translator,
    Node,
    Attribute,
    Translator\NodeTranslator\Visitor\Attributes,
};
use Innmind\Url\Url;
use Innmind\Immutable\{
    Set,
    Maybe,
};

/**
 * @psalm-immutable
 */
final class LinkTranslator implements NodeTranslator
{
    private function __construct()
    {
    }

    #[\Override]
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
            ->filter(static fn(\DOMElement $node) => $node->tagName === 'link')
            ->flatMap(
                fn(\DOMElement $node) => Attributes::of()($node)->flatMap(
                    $this->build(...),
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

    /**
     * @param Set<Attribute> $attributes
     *
     * @return Maybe<Link>
     */
    private function build(Set $attributes): Maybe
    {
        /** @var non-empty-string */
        $rel = $attributes
            ->find(static fn($attribute) => $attribute->name() === 'rel')
            ->map(static fn($rel) => $rel->value())
            ->filter(static fn($rel) => $rel !== '')
            ->match(
                static fn($rel) => $rel,
                static fn() => 'related',
            );

        return $attributes
            ->find(static fn($attribute) => $attribute->name() === 'href')
            ->flatMap(static fn($href) => Url::maybe($href->value()))
            ->map(static fn($href) => Link::of(
                $href,
                $rel,
                $attributes,
            ));
    }
}
