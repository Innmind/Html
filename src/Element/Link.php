<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\{
    Element,
    Element\Name,
    Element\Custom,
    Attribute,
};
use Innmind\Url\Url;
use Innmind\Immutable\Sequence;

/**
 * @psalm-immutable
 */
final class Link implements Custom
{
    /**
     * @param non-empty-string $relationship
     */
    private function __construct(
        private Url $href,
        private string $relationship,
        private Element $element,
    ) {
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $relationship
     * @param Sequence<Attribute>|null $attributes
     */
    public static function of(
        Url $href,
        string $relationship,
        ?Sequence $attributes = null,
    ): self {
        return new self(
            $href,
            $relationship,
            Element::selfClosing(
                Name::of('link'),
                $attributes,
            ),
        );
    }

    public function href(): Url
    {
        return $this->href;
    }

    /**
     * @return non-empty-string
     */
    public function relationship(): string
    {
        return $this->relationship;
    }

    #[\Override]
    public function normalize(): Element
    {
        return $this
            ->element
            ->addAttribute(Attribute::of('rel', $this->relationship))
            ->addAttribute(Attribute::of('href', $this->href->toString()));
    }
}
