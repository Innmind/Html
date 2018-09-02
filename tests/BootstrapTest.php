<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html;

use function Innmind\Html\bootstrap;
use Innmind\Html\Reader\Reader;
use PHPUnit\Framework\TestCase;

class BootstrapTest extends TestCase
{
    public function testBootstrap()
    {
        $this->assertInstanceOf(Reader::class, bootstrap());
    }
}
