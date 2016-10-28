<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Node\Document,
    Exception\InvalidArgumentException
};
use Innmind\Xml\{
    NodeInterface,
    Translator\NodeTranslatorInterface,
    Translator\NodeTranslator,
    Node\Document\Type
};
use Innmind\Immutable\Map;

final class DocumentTranslator implements NodeTranslatorInterface
{
    public function translate(
        \DOMNode $node,
        NodeTranslator $translator
    ): NodeInterface {
        if ($node->nodeType !== XML_HTML_DOCUMENT_NODE) {
            throw new InvalidArgumentException;
        }

        return new Document(
            $node->doctype ? $this->buildDoctype($node->doctype) : new Type('html'),
            $node->childNodes ?
                $this->buildChildren($node->childNodes, $translator) : null
        );
    }

    private function buildDoctype(\DOMDocumentType $type): Type
    {
        return new Type(
            $type->name,
            $type->publicId,
            $type->systemId
        );
    }

    private function buildChildren(
        \DOMNodeList $nodes,
        NodeTranslator $translator
    ): Map {
        $children = new Map('int', NodeInterface::class);

        foreach ($nodes as $child) {
            if ($child->nodeType === XML_DOCUMENT_TYPE_NODE) {
                continue;
            }

            $children = $children->put(
                $children->size(),
                $translator->translate($child)
            );
        }

        return $children;
    }
}
