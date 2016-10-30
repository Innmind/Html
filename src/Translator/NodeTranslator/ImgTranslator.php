<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Exception\InvalidArgumentException,
    Exception\MissingSrcAttributeException,
    Element\Img
};
use Innmind\Xml\{
    Translator\NodeTranslatorInterface,
    Translator\NodeTranslator,
    NodeInterface,
    Translator\NodeTranslator\Visitor\Attributes
};
use Innmind\Url\Url;

final class ImgTranslator implements NodeTranslatorInterface
{
    public function translate(
        \DOMNode $node,
        NodeTranslator $translator
    ): NodeInterface {
        if (
            !$node instanceof \DOMElement ||
            $node->tagName !== 'img'
        ) {
            throw new InvalidArgumentException;
        }

        $attributes = (new Attributes)($node);

        if (!$attributes->contains('src')) {
            throw new MissingSrcAttributeException;
        }

        return new Img(
            Url::fromString($attributes->get('src')->value()),
            $attributes
        );
    }
}
