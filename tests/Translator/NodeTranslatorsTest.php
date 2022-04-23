<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator;

use Innmind\Html\Translator\{
    NodeTranslators,
    NodeTranslator\DocumentTranslator,
    NodeTranslator\ElementTranslator,
};
use Innmind\Xml\Translator\NodeTranslator;
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class NodeTranslatorsTest extends TestCase
{
    public function testDefaults()
    {
        $defaults = NodeTranslators::defaults();

        $this->assertInstanceOf(Map::class, $defaults);
        $this->assertSame('int', $defaults->keyType());
        $this->assertSame(
            NodeTranslator::class,
            $defaults->valueType(),
        );
        $this->assertCount(2, $defaults);
        $this->assertInstanceOf(
            DocumentTranslator::class,
            $defaults->get(\XML_HTML_DOCUMENT_NODE),
        );
        $this->assertInstanceOf(
            ElementTranslator::class,
            $defaults->get(\XML_ELEMENT_NODE),
        );
        $this->assertSame($defaults, NodeTranslators::defaults());
    }
}
