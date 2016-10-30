<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Exception\InvalidArgumentException,
    Exception\MissingHrefAttributeException,
    Exception\InvalidLinkException,
    Element\Link
};
use Innmind\Xml\{
    Translator\NodeTranslatorInterface,
    Translator\NodeTranslator,
    NodeInterface,
    Translator\NodeTranslator\Visitor\Attributes
};
use Innmind\Url\{
    Url,
    Exception\ExceptionInterface
};

final class LinkTranslator implements NodeTranslatorInterface
{
    public function translate(
        \DOMNode $node,
        NodeTranslator $translator
    ): NodeInterface {
        if (
            !$node instanceof \DOMElement ||
            $node->tagName !== 'link'
        ) {
            throw new InvalidArgumentException;
        }

        $attributes = (new Attributes)($node);

        if (!$attributes->contains('href')) {
            throw new MissingHrefAttributeException;
        }

        try {
            return new Link(
                Url::fromString($attributes->get('href')->value()),
                $attributes->contains('rel') ?
                    $attributes->get('rel')->value() : 'related',
                $attributes
            );
        } catch (ExceptionInterface $e) {
            throw new InvalidLinkException;
        }
    }
}