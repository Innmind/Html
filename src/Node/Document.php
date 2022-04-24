<?php
declare(strict_types = 1);

namespace Innmind\Html\Node;

use Innmind\Xml\{
    Node,
    Node\Document\Type,
};
use Innmind\Immutable\{
    Sequence,
    Str,
};

/**
 * @psalm-immutable
 */
final class Document implements Node
{
    private Type $type;
    /** @var Sequence<Node> */
    private Sequence $children;

    /**
     * @param Sequence<Node>|null $children
     */
    private function __construct(Type $type, Sequence $children = null)
    {
        $this->type = $type;
        $this->children = $children ?? Sequence::of();
    }

    /**
     * @psalm-pure
     *
     * @param Sequence<Node>|null $children
     */
    public static function of(Type $type, Sequence $children = null): self
    {
        return new self($type, $children);
    }

    public function type(): Type
    {
        return $this->type;
    }

    public function children(): Sequence
    {
        return $this->children;
    }

    public function hasChildren(): bool
    {
        return !$this->children->empty();
    }

    public function filterChild(callable $filter): self
    {
        $document = clone $this;
        $document->children = $this->children->filter($filter);

        return $document;
    }

    public function mapChild(callable $map): self
    {
        $document = clone $this;
        $document->children = $this->children->map($map);

        return $document;
    }

    public function prependChild(Node $child): Node
    {
        $document = clone $this;
        $document->children = Sequence::of(
            $child,
            ...$this->children->toList(),
        );

        return $document;
    }

    public function appendChild(Node $child): Node
    {
        $document = clone $this;
        $document->children = ($this->children)($child);

        return $document;
    }

    public function content(): string
    {
        $children = $this->children->map(
            static fn(Node $child): string => $child->toString(),
        );

        return Str::of('')->join($children)->toString();
    }

    public function toString(): string
    {
        return $this->type->toString()."\n".$this->content();
    }
}
