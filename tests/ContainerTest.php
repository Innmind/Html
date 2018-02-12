<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html;

use Innmind\Html\Reader\Reader;
use Innmind\Compose\{
    ContainerBuilder\ContainerBuilder,
    Loader\Yaml
};
use Innmind\Url\Path;
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testService()
    {
        $container = (new ContainerBuilder(new Yaml))(
            new Path('container.yml'),
            new Map('string', 'mixed')
        );

        $this->assertInstanceOf(Reader::class, $container->get('reader'));
    }
}
