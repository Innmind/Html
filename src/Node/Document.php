<?php
declare(strict_types = 1);

namespace Innmind\Html\Node;

use Innmind\Xml\{
    Node,
    Node\Document\Type,
    AsContent,
};
use Innmind\Filesystem\File\{
    Content,
    Content\Line,
};
use Innmind\Immutable\{
    Sequence,
    Str,
};

/**
 * @psalm-immutable
 */
final class Document implements Node, AsContent
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
        return new self(
            $this->type,
            $this->children->filter($filter),
        );
    }

    public function mapChild(callable $map): self
    {
        return new self(
            $this->type,
            $this->children->map($map),
        );
    }

    public function prependChild(Node $child): Node
    {
        return new self(
            $this->type,
            Sequence::of(
                $child,
                ...$this->children->toList(),
            ),
        );
    }

    public function appendChild(Node $child): Node
    {
        return new self(
            $this->type,
            ($this->children)($child),
        );
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

    public function asContent(): Content
    {
        return Content::ofLines(
            Sequence::lazyStartingWith(Line::of(Str::of($this->type->toString())))->append(
                $this->children->flatMap(
                    static fn($child) => match ($child instanceof AsContent) {
                        true => $child->asContent()->lines(),
                        false => Content::ofString($child->toString())->lines(),
                    },
                ),
            ),
        );
    }
}
