<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\Element\SelfClosingElement;
use Innmind\Url\UrlInterface;
use Innmind\Immutable\MapInterface;

final class Base extends SelfClosingElement
{
    private UrlInterface $href;

    public function __construct(
        UrlInterface $href,
        MapInterface $attributes = null
    ) {
        parent::__construct('base', $attributes);
        $this->href = $href;
    }

    public function href(): UrlInterface
    {
        return $this->href;
    }
}
