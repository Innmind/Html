<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\{
    Element,
    Node,
    Attribute,
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
final class A implements Element
{
    private Element\Element $element;
    private Url $href;

    /**
     * @param Set<Attribute>|null $attributes
     * @param Sequence<Node>|null $children
     */
    public function __construct(
        Url $href,
        Set $attributes = null,
        Sequence $children = null,
    ) {
        $this->element = Element\Element::of(
            'a',
            $attributes,
            $children,
        );
        $this->href = $href;
    }

    public function href(): Url
    {
        return $this->href;
    }

    public function name(): string
    {
        return 'a';
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
