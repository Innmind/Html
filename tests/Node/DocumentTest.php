<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Node;

use Innmind\Html\Node\Document;
use Innmind\Xml\{
    NodeInterface,
    Node\Document\Type,
    Node\Text,
    Element\Element
};
use Innmind\Immutable\{
    Map,
    MapInterface
};

class DocumentTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeInterface::class,
            new Document(new Type('html'))
        );
    }

    public function testType()
    {
        $type = new Type('html');

        $this->assertSame(
            $type,
            (new Document($type))->type()
        );
    }

    public function testWithoutChildren()
    {
        $document = new Document(new Type('html'));

        $this->assertInstanceOf(MapInterface::class, $document->children());
        $this->assertSame('int', (string) $document->children()->keyType());
        $this->assertSame(
            NodeInterface::class,
            (string) $document->children()->valueType()
        );
        $this->assertFalse($document->hasChildren());
    }

    public function testWithChildren()
    {
        $document = new Document(
            new Type('html'),
            $children = (new Map('int', NodeInterface::class))
                ->put(0, $this->createMock(NodeInterface::class))
        );

        $this->assertSame($children, $document->children());
        $this->assertTrue($document->hasChildren());
    }

    /**
     * @expectedException Innmind\Html\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidChildren()
    {
        new Document(
            new Type('html'),
            new Map('int', 'string')
        );
    }

    public function testContent()
    {
        $document = new Document(
            new Type('html'),
            (new Map('int', NodeInterface::class))
                ->put(
                    0,
                    new Element(
                        'html',
                        null,
                        (new Map('int', NodeInterface::class))
                            ->put(0, new Text('wat'))
                    )
                )
        );

        $this->assertSame('<html>wat</html>', $document->content());
    }

    public function testCast()
    {
        $document = new Document(
            new Type('html'),
            (new Map('int', NodeInterface::class))
                ->put(
                    0,
                    new Element(
                        'html',
                        null,
                        (new Map('int', NodeInterface::class))
                            ->put(0, new Text('wat'))
                    )
                )
        );

        $this->assertSame(
            '<!DOCTYPE html>'."\n".'<html>wat</html>',
            (string) $document
        );
    }
}
