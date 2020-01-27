<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\{
    Element\SelfClosingElement,
    Attribute,
};
use Innmind\Url\Url;
use Innmind\Immutable\Set;

final class Img extends SelfClosingElement
{
    private Url $src;

    /**
     * @param Set<Attribute>|null $attributes
     */
    public function __construct(
        Url $src,
        Set $attributes = null
    ) {
        parent::__construct('img', $attributes);
        $this->src = $src;
    }

    public function src(): Url
    {
        return $this->src;
    }
}
