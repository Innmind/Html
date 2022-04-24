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

    private function __construct(
        Url $href,
        string $relationship,
        SelfClosingElement $element,
    ) {
        if (Str::of($relationship)->empty()) {
            throw new DomainException;
        }

        $this->element = $element;
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
        return new self(
            $href,
            $relationship,
            SelfClosingElement::of('link', $attributes),
        );
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
        return new self(
            $this->href,
            $this->relationship,
            $this->element->removeAttribute($name),
        );
    }

    public function addAttribute(Attribute $attribute): self
    {
        return new self(
            $this->href,
            $this->relationship,
            $this->element->addAttribute($attribute),
        );
    }

    public function children(): Sequence
    {
        return $this->element->children();
    }

    public function filterChild(callable $filter): self
    {
        return new self(
            $this->href,
            $this->relationship,
            $this->element->filterChild($filter),
        );
    }

    public function mapChild(callable $map): self
    {
        return new self(
            $this->href,
            $this->relationship,
            $this->element->mapChild($map),
        );
    }

    public function prependChild(Node $child): self
    {
        return new self(
            $this->href,
            $this->relationship,
            $this->element->prependChild($child),
        );
    }

    public function appendChild(Node $child): self
    {
        return new self(
            $this->href,
            $this->relationship,
            $this->element->appendChild($child),
        );
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
