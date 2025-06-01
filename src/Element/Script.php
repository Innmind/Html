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

    private function __construct(Element\Element $element)
    {
        $this->element = $element;
    }

    /**
     * @psalm-pure
     *
     * @param Set<Attribute>|null $attributes
     */
    public static function of(Text $text, ?Set $attributes = null): self
    {
        /** @var Sequence<Node> */
        $children = Sequence::of($text);

        return new self(Element\Element::of('script', $attributes, $children));
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
        return new self($this->element->removeAttribute($name));
    }

    public function addAttribute(Attribute $attribute): self
    {
        return new self($this->element->addAttribute($attribute));
    }

    public function children(): Sequence
    {
        return $this->element->children();
    }

    public function filterChild(callable $filter): self
    {
        return new self($this->element->filterChild($filter));
    }

    public function mapChild(callable $map): self
    {
        return new self($this->element->mapChild($map));
    }

    public function prependChild(Node $child): self
    {
        return new self($this->element->prependChild($child));
    }

    public function appendChild(Node $child): self
    {
        return new self($this->element->appendChild($child));
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
