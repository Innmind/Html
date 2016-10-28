<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator;

use Innmind\Html\Translator\{
    NodeTranslators,
    NodeTranslator\DocumentTranslator
};
use Innmind\Xml\Translator\NodeTranslatorInterface;
use Innmind\Immutable\MapInterface;

class NodeTranslatorsTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaults()
    {
        $defaults = NodeTranslators::defaults();

        $this->assertInstanceOf(MapInterface::class, $defaults);
        $this->assertSame('int', (string) $defaults->keyType());
        $this->assertSame(
            NodeTranslatorInterface::class,
            (string) $defaults->valueType()
        );
        $this->assertCount(1, $defaults);
        $this->assertInstanceOf(
            DocumentTranslator::class,
            $defaults->get(XML_HTML_DOCUMENT_NODE)
        );
        $this->assertSame($defaults, NodeTranslators::defaults());
    }
}
