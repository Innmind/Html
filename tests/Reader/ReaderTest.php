<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Reader;

use Innmind\Html\{
    Reader\Reader,
    Translator\NodeTranslators as HtmlTranslators
};
use Innmind\Xml\{
    ReaderInterface,
    Translator\NodeTranslator,
    Translator\NodeTranslators,
    NodeInterface
};
use Innmind\Filesystem\Stream\StringStream;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
    private $reader;

    public function setUp()
    {
        $this->reader = new Reader(
            new NodeTranslator(
                NodeTranslators::defaults()->merge(
                    HtmlTranslators::defaults()
                )
            )
        );
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            ReaderInterface::class,
            $this->reader
        );
    }

    public function testReadSimple()
    {
        $node = $this->reader->read(
            new StringStream($html = <<<HTML
<!DOCTYPE html>
<html>
    <head></head>
    <body>
        foo
    </body>
</html>
HTML
            )
        );
        $expected = <<<HTML
<!DOCTYPE html>
<html><head/><body>
        foo
    </body></html>
HTML;

        $this->assertInstanceOf(NodeInterface::class, $node);
        $this->assertSame($expected, (string) $node);
    }
}
