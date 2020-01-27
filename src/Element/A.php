<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\Element\Element;
use Innmind\Url\UrlInterface;
use Innmind\Immutable\MapInterface;

final class A extends Element
{
    private UrlInterface $href;

    public function __construct(
        UrlInterface $href,
        MapInterface $attributes = null,
        MapInterface $children = null
    ) {
        parent::__construct('a', $attributes, $children);
        $this->href = $href;
    }

    public function href(): UrlInterface
    {
        return $this->href;
    }
}
