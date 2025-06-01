<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\Element\Img;
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
final class ImgTranslator implements NodeTranslator
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
            ->filter(static fn(\DOMElement $node) => $node->tagName === 'img')
            ->flatMap(
                static fn(\DOMElement $node) => Attributes::of()($node)->flatMap(
                    static fn($attributes) => $attributes
                        ->find(static fn($attribute) => $attribute->name() === 'src')
                        ->flatMap(static fn($src) => Url::maybe($src->value()))
                        ->map(static fn($src) => Img::of($src, $attributes)),
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
