<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Node\Document,
    Exception\InvalidArgumentException
};
use Innmind\Xml\{
    Node,
    Translator\NodeTranslator,
    Translator\Translator,
    Node\Document\Type
};
use Innmind\Immutable\Map;

final class DocumentTranslator implements NodeTranslator
{
    public function __invoke(
        \DOMNode $node,
        Translator $translate
    ): Node {
        if ($node->nodeType !== XML_HTML_DOCUMENT_NODE) {
            throw new InvalidArgumentException;
        }

        return new Document(
            $node->doctype ? $this->buildDoctype($node->doctype) : new Type('html'),
            $node->childNodes ?
                $this->buildChildren($node->childNodes, $translate) : null
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
        Translator $translate
    ): Map {
        $children = new Map('int', Node::class);

        foreach ($nodes as $child) {
            if ($child->nodeType === XML_DOCUMENT_TYPE_NODE) {
                continue;
            }

            $children = $children->put(
                $children->size(),
                $translate($child)
            );
        }

        return $children;
    }
}
