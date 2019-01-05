<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Node;

use Innmind\Html\{
    Node\Document,
    Exception\InvalidArgumentException,
    Exception\OutOfBoundsException,
};
use Innmind\Xml\{
    Node,
    Node\Document\Type,
    Node\Text,
    Element\Element,
};
use Innmind\Immutable\{
    MapInterface,
    Map,
};
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Node::class,
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
            Node::class,
            (string) $document->children()->valueType()
        );
        $this->assertFalse($document->hasChildren());
    }

    public function testWithChildren()
    {
        $document = new Document(
            new Type('html'),
            $children = (new Map('int', Node::class))
                ->put(0, $this->createMock(Node::class))
        );

        $this->assertSame($children, $document->children());
        $this->assertTrue($document->hasChildren());
    }

    public function testThrowWhenInvalidChildren()
    {
        $this->expectException(InvalidArgumentException::class);

        new Document(
            new Type('html'),
            new Map('int', 'string')
        );
    }

    public function testContent()
    {
        $document = new Document(
            new Type('html'),
            (new Map('int', Node::class))
                ->put(
                    0,
                    new Element(
                        'html',
                        null,
                        (new Map('int', Node::class))
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
            (new Map('int', Node::class))
                ->put(
                    0,
                    new Element(
                        'html',
                        null,
                        (new Map('int', Node::class))
                            ->put(0, new Text('wat'))
                    )
                )
        );

        $this->assertSame(
            '<!DOCTYPE html>'."\n".'<html>wat</html>',
            (string) $document
        );
    }

    public function testRemoveChild()
    {
        $document = new Document(
            new Type('html'),
            (new Map('int', Node::class))
                ->put(0, new Element('foo'))
                ->put(1, new Element('bar'))
                ->put(2, new Element('baz'))
        );

        $document2 = $document->removeChild(1);

        $this->assertNotSame($document, $document2);
        $this->assertInstanceOf(Document::class, $document2);
        $this->assertSame($document->type(), $document2->type());
        $this->assertCount(3, $document->children());
        $this->assertCount(2, $document2->children());
        $this->assertSame(
            $document->children()->get(0),
            $document2->children()->get(0)
        );
        $this->assertSame(
            $document->children()->get(2),
            $document2->children()->get(1)
        );
    }

    public function testThrowWhenRemovingUnknownChild()
    {
        $this->expectException(OutOfBoundsException::class);

        (new Document(
            new Type('html'),
            (new Map('int', Node::class))
                ->put(0, new Element('foo'))
                ->put(1, new Element('bar'))
                ->put(2, new Element('baz'))
        ))->removeChild(3);
    }

    public function testReplaceChild()
    {
        $document = new Document(
            new Type('html'),
            (new Map('int', Node::class))
                ->put(0, new Element('foo'))
                ->put(1, new Element('bar'))
                ->put(2, new Element('baz'))
        );

        $document2 = $document->replaceChild(
            1,
            $node = $this->createMock(Node::class)
        );

        $this->assertNotSame($document, $document2);
        $this->assertInstanceOf(Document::class, $document2);
        $this->assertSame($document->type(), $document2->type());
        $this->assertCount(3, $document->children());
        $this->assertCount(3, $document2->children());
        $this->assertSame(
            $document->children()->get(0),
            $document2->children()->get(0)
        );
        $this->assertNotSame(
            $document->children()->get(1),
            $document2->children()->get(1)
        );
        $this->assertSame($node, $document2->children()->get(1));
        $this->assertSame(
            $document->children()->get(2),
            $document2->children()->get(2)
        );
    }

    public function testThrowWhenReplacingUnknownChild()
    {
        $this->expectException(OutOfBoundsException::class);

        (new Document(
            new Type('html'),
            (new Map('int', Node::class))
                ->put(0, new Element('foo'))
                ->put(1, new Element('bar'))
                ->put(2, new Element('baz'))
        ))->replaceChild(
            3,
            $this->createMock(Node::class)
        );
    }

    public function testPrependChild()
    {
        $document = new Document(
            new Type('html'),
            (new Map('int', Node::class))
                ->put(0, new Element('foo'))
                ->put(1, new Element('bar'))
                ->put(2, new Element('baz'))
        );

        $document2 = $document->prependChild(
            $node = $this->createMock(Node::class)
        );

        $this->assertNotSame($document, $document2);
        $this->assertInstanceOf(Document::class, $document2);
        $this->assertSame($document->type(), $document2->type());
        $this->assertNotSame($document->children(), $document2->children());
        $this->assertCount(3, $document->children());
        $this->assertCount(4, $document2->children());
        $this->assertSame(
            $node,
            $document2->children()->get(0)
        );
        $this->assertSame(
            $document->children()->get(0),
            $document2->children()->get(1)
        );
        $this->assertSame(
            $document->children()->get(1),
            $document2->children()->get(2)
        );
        $this->assertSame(
            $document->children()->get(2),
            $document2->children()->get(3)
        );
    }

    public function testAopendChild()
    {
        $document = new Document(
            new Type('html'),
            (new Map('int', Node::class))
                ->put(0, new Element('foo'))
                ->put(1, new Element('bar'))
                ->put(2, new Element('baz'))
        );

        $document2 = $document->appendChild(
            $node = $this->createMock(Node::class)
        );

        $this->assertNotSame($document, $document2);
        $this->assertInstanceOf(Document::class, $document2);
        $this->assertSame($document->type(), $document2->type());
        $this->assertNotSame($document->children(), $document2->children());
        $this->assertCount(3, $document->children());
        $this->assertCount(4, $document2->children());
        $this->assertSame(
            $document->children()->get(0),
            $document2->children()->get(0)
        );
        $this->assertSame(
            $document->children()->get(1),
            $document2->children()->get(1)
        );
        $this->assertSame(
            $document->children()->get(2),
            $document2->children()->get(2)
        );
        $this->assertSame(
            $node,
            $document2->children()->get(3)
        );
    }
}
