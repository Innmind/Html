<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Html\Exception\DomainException;
use Innmind\Xml\{
    Element\SelfClosingElement,
    Element,
    Attribute,
    Node,
};
use Innmind\Url\Url;
use Innmind\Immutable\{
    Set,
    Str,
    Sequence,
    Maybe,
    Map,
};

/**
 * @psalm-immutable
 */
final class Link implements Element
{
    private SelfClosingElement $element;
    private Url $href;
    private string $relationship;

    /**
     * @param Set<Attribute>|null $attributes
     */
    private function __construct(
        Url $href,
        string $relationship,
        Set $attributes = null,
    ) {
        if (Str::of($relationship)->empty()) {
            throw new DomainException;
        }

        $this->element = SelfClosingElement::of('link', $attributes);
        $this->href = $href;
        $this->relationship = $relationship;
    }

    /**
     * @psalm-pure
     *
     * @param Set<Attribute>|null $attributes
     */
    public static function of(
        Url $href,
        string $relationship,
        Set $attributes = null,
    ): self {
        return new self($href, $relationship, $attributes);
    }

    public function href(): Url
    {
        return $this->href;
    }

    public function relationship(): string
    {
        return $this->relationship;
    }

    public function name(): string
    {
        return 'link';
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
