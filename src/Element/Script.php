<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\{
    Node,
    Attribute,
    Element,
    Node\Text,
};
use Innmind\Immutable\{
    Set,
    Sequence,
    Maybe,
    Map,
};

/**
 * @psalm-immutable
 */
final class Script implements Element
{
    private Element\Element $element;

    /**
     * @param Set<Attribute>|null $attributes
     */
    private function __construct(Text $text, Set $attributes = null)
    {
        /** @var Sequence<Node> */
        $children = Sequence::of($text);
        $this->element = Element\Element::of('script', $attributes, $children);
    }

    /**
     * @psalm-pure
     *
     * @param Set<Attribute>|null $attributes
     */
    public static function of(Text $text, Set $attributes = null): self
    {
        return new self($text, $attributes);
    }

    public function name(): string
    {
        return 'script';
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
