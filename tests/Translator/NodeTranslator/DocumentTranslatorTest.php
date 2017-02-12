<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\DocumentTranslator,
    Node\Document
};
use Innmind\Xml\{
    Translator\NodeTranslatorInterface,
    Translator\NodeTranslator,
    Translator\NodeTranslators
};
use PHPUnit\Framework\TestCase;

class DocumentTranslatorTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslatorInterface::class,
            new DocumentTranslator
        );
    }

    public function testTranslate()
    {
        $document = new \DOMDocument;
        $document->loadHtml('<!DOCTYPE html><body></body>');

        $node = (new DocumentTranslator)->translate(
            $document,
            new NodeTranslator(NodeTranslators::defaults())
        );

        $this->assertInstanceOf(Document::class, $node);
        $this->assertSame('html', $node->type()->name());
        $this->assertCount(1, $node->children());
        $this->assertSame('<html><body/></html>', $node->content());
    }

    public function testTranslateWithoutDoctype()
    {
        $document = new \DOMDocument;
        $document->loadHtml('<!--foo-->');

        $node = (new DocumentTranslator)->translate(
            $document,
            new NodeTranslator(NodeTranslators::defaults())
        );

        $this->assertSame(
            '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">',
            (string) $node->type()
        );
    }

    public function testTranslateWithoutChildren()
    {
        $document = new \DOMDocument;
        $document->loadHtml('<!DOCTYPE html>');

        $node = (new DocumentTranslator)->translate(
            $document,
            new NodeTranslator(NodeTranslators::defaults())
        );

        $this->assertFalse($node->hasChildren());
    }

    /**
     * @expectedException Innmind\Html\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidNode()
    {
        $document = new \DOMDocument;
        $document->loadXML('<foo></foo>');

        (new DocumentTranslator)->translate(
            $document->childNodes->item(0),
            new NodeTranslator(NodeTranslators::defaults())
        );
    }
}
