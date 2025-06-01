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
final class Base implements Element
{
    private SelfClosingElement $element;
    private Url $href;

    private function __construct(Url $href, SelfClosingElement $element)
    {
        $this->element = $element;
        $this->href = $href;
    }

    /**
     * @psalm-pure
     *
     * @param Set<Attribute>|null $attributes
     */
    public static function of(Url $href, ?Set $attributes = null): self
    {
        return new self($href, SelfClosingElement::of('base', $attributes));
    }

    public function href(): Url
    {
        return $this->href;
    }

    #[\Override]
    public function name(): string
    {
        return 'base';
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
            $this->href,
            $this->element->removeAttribute($name),
        );
    }

    #[\Override]
    public function addAttribute(Attribute $attribute): self
    {
        return new self(
            $this->href,
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
            $this->href,
            $this->element->filterChild($filter),
        );
    }

    #[\Override]
    public function mapChild(callable $map): self
    {
        return new self(
            $this->href,
            $this->element->mapChild($map),
        );
    }

    #[\Override]
    public function prependChild(Node $child): self
    {
        return new self(
            $this->href,
            $this->element->prependChild($child),
        );
    }

    #[\Override]
    public function appendChild(Node $child): self
    {
        return new self(
            $this->href,
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
            ->addAttribute(Attribute::of('href', $this->href->toString()))
            ->toString();
    }
}
