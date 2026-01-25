<?php
declare(strict_types = 1);

namespace Innmind\Html;

use Innmind\Xml\{
    Node,
    Element,
    Element\Custom,
    Document\Type,
    Format,
    Document as XMLDocument,
};
use Innmind\Filesystem\File\Content;
use Innmind\Immutable\{
    Sequence,
    Maybe,
};

/**
 * @psalm-immutable
 */
final class Document
{
    /**
     * @param Sequence<Node|Element|Custom> $children
     */
    private function __construct(
        private Type $type,
        private Sequence $children,
    ) {
    }

    /**
     * @psalm-pure
     *
     * @param Sequence<Node|Element|Custom>|null $children
     */
    #[\NoDiscard]
    public static function of(Type $type, ?Sequence $children = null): self
    {
        return new self($type, $children ?? Sequence::of());
    }

    #[\NoDiscard]
    public function type(): Type
    {
        return $this->type;
    }

    /**
     * @return Sequence<Node|Element|Custom>
     */
    #[\NoDiscard]
    public function children(): Sequence
    {
        return $this->children;
    }

    #[\NoDiscard]
    public function hasChildren(): bool
    {
        return !$this->children->empty();
    }

    /**
     * @param callable(Node|Element|Custom): bool $filter
     */
    #[\NoDiscard]
    public function filterChild(callable $filter): self
    {
        return new self(
            $this->type,
            $this->children->filter($filter),
        );
    }

    /**
     * @param callable(Node|Element|Custom): (Node|Element|Custom) $map
     */
    #[\NoDiscard]
    public function mapChild(callable $map): self
    {
        return new self(
            $this->type,
            $this->children->map($map),
        );
    }

    #[\NoDiscard]
    public function prependChild(Node|Element|Custom $child): self
    {
        return new self(
            $this->type,
            $this->children->prepend(Sequence::of($child)),
        );
    }

    #[\NoDiscard]
    public function appendChild(Node|Element|Custom $child): self
    {
        return new self(
            $this->type,
            ($this->children)($child),
        );
    }

    #[\NoDiscard]
    public function asContent(Format $format = Format::pretty): Content
    {
        /** @var Maybe<XMLDocument\Encoding> */
        $encoding = Maybe::nothing();
        $chunks = XMLDocument::of(
            XMLDocument\Version::of(1, 0),
            Maybe::just($this->type),
            $encoding,
            $this->children,
        )
            ->asContent($format)
            ->chunks()
            ->map(static fn($chunk) => match ($chunk->startsWith('<?xml version="1.0"?>')) {
                true => $chunk->drop(22),
                false => $chunk,
            });

        return Content::ofChunks($chunks);
    }
}
