<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\Element\SelfClosingElement;
use Innmind\Url\Url;
use Innmind\Immutable\Set;

final class Base extends SelfClosingElement
{
    private Url $href;

    public function __construct(
        Url $href,
        Set $attributes = null
    ) {
        parent::__construct('base', $attributes);
        $this->href = $href;
    }

    public function href(): Url
    {
        return $this->href;
    }
}
