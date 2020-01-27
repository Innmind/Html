<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Exception\InvalidArgumentException,
    Exception\MissingSrcAttribute,
    Element\Img,
};
use Innmind\Xml\{
    Translator\NodeTranslator,
    Translator\Translator,
    Node,
    Attribute,
    Translator\NodeTranslator\Visitor\Attributes,
};
use Innmind\Url\Url;

final class ImgTranslator implements NodeTranslator
{
    public function __invoke(
        \DOMNode $node,
        Translator $translate
    ): Node {
        if (
            !$node instanceof \DOMElement ||
            $node->tagName !== 'img'
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

        if (!$map->contains('src')) {
            throw new MissingSrcAttribute;
        }

        return new Img(
            Url::of($map->get('src')->value()),
            $attributes,
        );
    }
}
