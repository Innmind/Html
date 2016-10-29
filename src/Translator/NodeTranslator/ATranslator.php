<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Exception\InvalidArgumentException,
    Exception\MissingHrefAttributeException,
    Element\A
};
use Innmind\Xml\{
    Translator\NodeTranslatorInterface,
    Translator\NodeTranslator,
    NodeInterface,
    Translator\NodeTranslator\Visitor\Attributes,
    Translator\NodeTranslator\Visitor\Children
};
use Innmind\Url\Url;

final class ATranslator implements NodeTranslatorInterface
{
    public function translate(
        \DOMNode $node,
        NodeTranslator $translator
    ): NodeInterface {
        if (
            !$node instanceof \DOMElement ||
            $node->tagName !== 'a'
        ) {
            throw new InvalidArgumentException;
        }

        $attributes = (new Attributes)($node);

        if (!$attributes->contains('href')) {
            throw new MissingHrefAttributeException;
        }

        return new A(
            Url::fromString($attributes->get('href')->value()),
            $attributes,
            (new Children($translator))($node)
        );
    }
}
