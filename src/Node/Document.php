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
    private function __construct(Type $type, ?Sequence $children = null)
    {
        $this->type = $type;
        $this->children = $children ?? Sequence::of();
    }

    /**
     * @psalm-pure
     *
     * @param Sequence<Node>|null $children
     */
    public static function of(Type $type, ?Sequence $children = null): self
    {
        return new self($type, $children);
    }

    public function type(): Type
    {
        return $this->type;
    }

    #[\Override]
    public function children(): Sequence
    {
        return $this->children;
    }

    public function hasChildren(): bool
    {
        return !$this->children->empty();
    }

    #[\Override]
    public function filterChild(callable $filter): self
    {
        return new self(
            $this->type,
            $this->children->filter($filter),
        );
    }

    #[\Override]
    public function mapChild(callable $map): self
    {
        return new self(
            $this->type,
            $this->children->map($map),
        );
    }

    #[\Override]
    public function prependChild(Node $child): Node
    {
        return new self(
            $this->type,
            $this->children->prepend(Sequence::of($child)),
        );
    }

    #[\Override]
    public function appendChild(Node $child): Node
    {
        return new self(
            $this->type,
            ($this->children)($child),
        );
    }

    #[\Override]
    public function content(): string
    {
        $children = $this->children->map(
            static fn(Node $child): string => $child->toString(),
        );

        return Str::of('')->join($children)->toString();
    }

    #[\Override]
    public function toString(): string
    {
        return $this->type->toString()."\n".$this->content();
    }

    #[\Override]
    public function asContent(): Content
    {
        /**
         * @psalm-suppress MixedArgumentTypeCoercion
         * @psalm-suppress UndefinedInterfaceMethod
         * @psalm-suppress MixedMethodCall
         */
        return Content::ofLines(
            $this
                ->children
                ->flatMap(
                    static fn($child) => match ($child instanceof AsContent) {
                        true => $child->asContent()->lines(),
                        false => Content::ofString($child->toString())->lines(),
                    },
                )
                ->prepend(
                    Sequence::of(Line::of(Str::of($this->type->toString()))),
                ),
        );
    }
}
