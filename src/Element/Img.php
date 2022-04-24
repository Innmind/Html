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

    /**
     * @param Set<Attribute>|null $attributes
     */
    public function __construct(
        Url $src,
        Set $attributes = null,
    ) {
        $this->element = SelfClosingElement::of('img', $attributes);
        $this->src = $src;
    }

    public function src(): Url
    {
        return $this->src;
    }

    public function name(): string
    {
        return 'img';
    }

    public function attributes(): Map
    {
        return $this->element->attributes();
    }

    public function attribute(string $name): Maybe
    {
        return $this->element->attribute($name);
    }

    public function removeAttribute(string $name): self
    {
        $self = clone $this;
        $self->element = $this->element->removeAttribute($name);

        return $self;
    }

    public function addAttribute(Attribute $attribute): self
    {
        $self = clone $this;
        $self->element = $this->element->addAttribute($attribute);

        return $self;
    }

    public function children(): Sequence
    {
        return $this->element->children();
    }

    public function filterChild(callable $filter): self
    {
        $self = clone $this;
        $self->element = $this->element->filterChild($filter);

        return $self;
    }

    public function mapChild(callable $map): self
    {
        $self = clone $this;
        $self->element = $this->element->mapChild($map);

        return $self;
    }

    public function prependChild(Node $child): self
    {
        $self = clone $this;
        $self->element = $this->element->prependChild($child);

        return $self;
    }

    public function appendChild(Node $child): self
    {
        $self = clone $this;
        $self->element = $this->element->appendChild($child);

        return $self;
    }

    public function content(): string
    {
        return $this->element->content();
    }

    public function toString(): string
    {
        return $this->element->toString();
    }
}
