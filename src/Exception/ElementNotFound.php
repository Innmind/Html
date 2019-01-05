<?php
declare(strict_types = 1);

namespace Innmind\Html\Exception;

use Innmind\Xml\Exception\NodeNotFound;

final class ElementNotFound extends NodeNotFound implements Exception
{
}
