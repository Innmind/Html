<?php
declare(strict_types = 1);

namespace Innmind\Html\Exception;

use Innmind\Xml\Exception\NodeNotFoundException;

final class HeadNotFoundException extends NodeNotFoundException implements ExceptionInterface
{
}
