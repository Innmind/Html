<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\{
    Element\SelfClosingElement,
    Element,
    Attribute,
    Node,
};
use Innmind\Url\Url;
use Innmind\Immutable\{
    Set,
    Sequence,
    Maybe,
    Map,
};

/**
 * @psalm-immutable
 */
final class Img implements Element
{
    private SelfClosingElement $element;
    private Url $src;

    private function __construct(Url $src, SelfClosingElement $element)
    {
        $this->element = $element;
        $this->src = $src;
    }

    /**
     * @psalm-pure
     *
     * @param Set<Attribute>|null $attributes
     */
    public static function of(Url $src, ?Set $attributes = null): self
    {
        return new self($src, SelfClosingElement::of('img', $attributes));
    }

    public function src(): Url
    {
        return $this->src;
    }

    #[\Override]
    public function name(): string
    {
        return 'img';
    }

    #[\Override]
    public function attributes(): Map
    {
        return $this->element->attributes();
    }

    #[\Override]
    public function attribute(string $name): Maybe
    {
        return $this->element->attribute($name);
    }

    #[\Override]
    public function removeAttribute(string $name): self
    {
        return new self(
            $this->src,
            $this->element->removeAttribute($name),
        );
    }

    #[\Override]
    public function addAttribute(Attribute $attribute): self
    {
        return new self(
            $this->src,
            $this->element->addAttribute($attribute),
        );
    }

    #[\Override]
    public function children(): Sequence
    {
        return $this->element->children();
    }

    #[\Override]
    public function filterChild(callable $filter): self
    {
        return new self(
            $this->src,
            $this->element->filterChild($filter),
        );
    }

    #[\Override]
    public function mapChild(callable $map): self
    {
        return new self(
            $this->src,
            $this->element->mapChild($map),
        );
    }

    #[\Override]
    public function prependChild(Node $child): self
    {
        return new self(
            $this->src,
            $this->element->prependChild($child),
        );
    }

    #[\Override]
    public function appendChild(Node $child): self
    {
        return new self(
            $this->src,
            $this->element->appendChild($child),
        );
    }

    #[\Override]
    public function content(): string
    {
        return $this->element->content();
    }

    #[\Override]
    public function toString(): string
    {
        return $this
            ->element
            ->addAttribute(Attribute::of('src', $this->src->toString()))
            ->toString();
    }
}
