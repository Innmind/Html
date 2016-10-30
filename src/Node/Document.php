<?php
declare(strict_types = 1);

namespace Innmind\Html\Node;

use Innmind\Html\Exception\InvalidArgumentException;
use Innmind\Xml\{
    NodeInterface,
    Node\Document\Type
};
use Innmind\Immutable\{
    Map,
    MapInterface
};

final class Document implements NodeInterface
{
    private $type;
    private $children;

    public function __construct(Type $type, MapInterface $children = null)
    {
        $children = $children ?? new Map('int', NodeInterface::class);

        if (
            (string) $children->keyType() !== 'int' ||
            (string) $children->valueType() !== NodeInterface::class
        ) {
            throw new InvalidArgumentException;
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

    public function content(): string
    {
        return (string) $this->children->join('');
    }

    public function __toString(): string
    {
        return (string) $this->type."\n".$this->content();
    }
}
