<?php
declare(strict_types = 1);

namespace Innmind\Html\Visitor;

final class Body extends Element
{
    public function __construct()
    {
        parent::__construct('body');
    }
}
