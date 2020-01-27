<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Exception\InvalidArgumentException,
    Exception\MissingHrefAttribute,
    Element\A,
};
use Innmind\Xml\{
    Translator\NodeTranslator,
    Translator\Translator,
    Node,
    Attribute,
    Translator\NodeTranslator\Visitor\Attributes,
    Translator\NodeTranslator\Visitor\Children,
};
use Innmind\Url\Url;
use function Innmind\Immutable\unwrap;

final class ATranslator implements NodeTranslator
{
    public function __invoke(
        \DOMNode $node,
        Translator $translate
    ): Node {
        if (
            !$node instanceof \DOMElement ||
            $node->tagName !== 'a'
        ) {
            throw new InvalidArgumentException;
        }

        $attributes = (new Attributes)($node);
        $map = $attributes->toMapOf(
            'string',
            Attribute::class,
            static function(Attribute $attribute): \Generator {
                yield $attribute->name() => $attribute;
            },
        );

        if (!$map->contains('href')) {
            throw new MissingHrefAttribute;
        }

        return new A(
            Url::of($map->get('href')->value()),
            $attributes,
            ...unwrap((new Children($translate))($node)),
        );
    }
}
