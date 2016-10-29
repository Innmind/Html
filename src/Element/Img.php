<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\Element\SelfClosingElement;
use Innmind\Url\UrlInterface;
use Innmind\Immutable\MapInterface;

final class Img extends SelfClosingElement
{
    private $src;

    public function __construct(
        UrlInterface $src,
        MapInterface $attributes = null
    ) {
        parent::__construct('img', $attributes);
        $this->src = $src;
    }

    public function src(): UrlInterface
    {
        return $this->src;
    }
}
