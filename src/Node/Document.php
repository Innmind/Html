<?php
declare(strict_types = 1);

namespace Innmind\Html\Node;

use Innmind\Html\Exception\OutOfBoundsException;
use Innmind\Xml\{
    Node,
    Node\Document\Type,
};
use Innmind\Immutable\Sequence;
use function Innmind\Immutable\{
    unwrap,
    join,
};

final class Document implements Node
{
    private Type $type;
    /** @var Sequence<Node> */
    private Sequence $children;

    public function __construct(Type $type, Node ...$children)
    {
        $this->type = $type;
        /** @var Sequence<Node> */
        $this->children = Sequence::of(Node::class, ...$children);
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

    public function removeChild(int $position): Node
    {
        if (!$this->children->indices()->contains($position)) {
            throw new OutOfBoundsException((string) $position);
        }

        $document = clone $this;
        $document->children = $this
            ->children
            ->take($position)
            ->append($this->children->drop($position + 1));

        return $document;
    }

    public function replaceChild(int $position, Node $child): Node
    {
        if (!$this->children->indices()->contains($position)) {
            throw new OutOfBoundsException((string) $position);
        }

        $document = clone $this;
        $document->children = $this
            ->children
            ->take($position)
            ->add($child)
            ->append($this->children->drop($position + 1));

        return $document;
    }

    public function prependChild(Node $child): Node
    {
        $document = clone $this;
        /** @var Sequence<Node> */
        $document->children = Sequence::of(
            Node::class,
            $child,
            ...unwrap($this->children),
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
        $children = $this->children->mapTo(
            'string',
            static fn(Node $child): string => $child->toString(),
        );

        return join('', $children)->toString();
    }

    public function toString(): string
    {
        return $this->type->toString()."\n".$this->content();
    }
}
