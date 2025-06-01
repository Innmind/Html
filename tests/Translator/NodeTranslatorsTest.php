<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator;

use Innmind\Html\Translator\{
    NodeTranslators,
    NodeTranslator\DocumentTranslator,
    NodeTranslator\ElementTranslator,
};
use Innmind\Immutable\Map;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class NodeTranslatorsTest extends TestCase
{
    public function testDefaults()
    {
        $defaults = NodeTranslators::defaults();

        $this->assertInstanceOf(Map::class, $defaults);
        $this->assertCount(2, $defaults);
        $this->assertInstanceOf(
            DocumentTranslator::class,
            $defaults->get(\XML_HTML_DOCUMENT_NODE)->match(
                static fn($translator) => $translator,
                static fn() => null,
            ),
        );
        $this->assertInstanceOf(
            ElementTranslator::class,
            $defaults->get(\XML_ELEMENT_NODE)->match(
                static fn($translator) => $translator,
                static fn() => null,
            ),
        );
        $this->assertEquals($defaults, NodeTranslators::defaults());
    }
}
