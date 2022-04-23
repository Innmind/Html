<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Exception\InvalidArgumentException,
    Exception\MissingHrefAttribute,
    Element\Base,
};
use Innmind\Xml\{
    Translator\NodeTranslator,
    Translator\Translator,
    Node,
    Attribute,
    Translator\NodeTranslator\Visitor\Attributes,
};
use Innmind\Url\Url;
use Innmind\Immutable\Map;

final class BaseTranslator implements NodeTranslator
{
    public function __invoke(
        \DOMNode $node,
        Translator $translate
    ): Node {
        if (
            !$node instanceof \DOMElement ||
            $node->tagName !== 'base'
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

        return new Base(
            Url::of($map->get('href')->value()),
            $attributes,
        );
    }
}
