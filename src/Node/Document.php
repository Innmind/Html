<?php
declare(strict_types = 1);

namespace Innmind\Html\Node;

use Innmind\Html\Exception\OutOfBoundsException;
use Innmind\Xml\{
    Node,
    Node\Document\Type,
};
use Innmind\Immutable\{
    MapInterface,
    Map,
};

final class Document implements Node
{
    private $type;
    private $children;

    public function __construct(Type $type, MapInterface $children = null)
    {
        $children = $children ?? new Map('int', Node::class);

        if (
            (string) $children->keyType() !== 'int' ||
            (string) $children->valueType() !== Node::class
        ) {
            throw new \TypeError(sprintf(
                'Argument 2 must be of type MapInterface<int, %s>',
                Node::class
            ));
        }

        $this->type = $type;
        $this->children = $children;
    }

    public function type(): Type
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function children(): MapInterface
    {
        return $this->children;
    }

    public function hasChildren(): bool
    {
        return $this->children->size() > 0;
    }

    public function removeChild(int $position): Node
    {
        if (!$this->children->contains($position)) {
            throw new OutOfBoundsException;
        }

        $document = clone $this;
        $document->children = $this
            ->children
            ->reduce(
                new Map('int', Node::class),
                function(Map $children, int $pos, Node $node) use ($position): Map {
                    if ($pos === $position) {
                        return $children;
                    }

                    return $children->put(
                        $children->size(),
                        $node
                    );
                }
            );

        return $document;
    }

    public function replaceChild(int $position, Node $node): Node
    {
        if (!$this->children->contains($position)) {
            throw new OutOfBoundsException;
        }

        $document = clone $this;
        $document->children = $this->children->put(
            $position,
            $node
        );

        return $document;
    }

    public function prependChild(Node $child): Node
    {
        $document = clone $this;
        $document->children = $this
            ->children
            ->reduce(
                Map::of('int', Node::class)
                    (0, $child),
                function(Map $children, int $position, Node $child): Map {
                    return $children->put(
                        $children->size(),
                        $child
                    );
                }
            );

        return $document;
    }

    public function appendChild(Node $child): Node
    {
        $document = clone $this;
        $document->children = $this->children->put(
            $this->children->size(),
            $child
        );

        return $document;
    }

    public function content(): string
    {
        return (string) $this->children->join('');
    }

    public function __toString(): string
    {
        return (string) $this->type."\n".$this->content();
    }
}
