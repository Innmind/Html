<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Reader;

use Innmind\Html\{
    Reader\Reader,
    Translator\NodeTranslators as HtmlTranslators,
    Node\Document
};
use Innmind\Xml\{
    ReaderInterface,
    Translator\NodeTranslator,
    Translator\NodeTranslators,
    Node\Document as XmlDocument
};
use Innmind\Stream\Readable\Stream;
use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
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
        $html = <<<HTML
<!DOCTYPE html>
<html>
    <head></head>
    <body>
        foo
    </body>
</html>
HTML;
        $res = fopen('php://temp', 'r+');
        fwrite($res, $html);
        $node = $this->reader->read(
            new Stream($res)
        );
        $expected = <<<HTML
<!DOCTYPE html>
<html><head/><body>
        foo
    </body></html>
HTML;

        $this->assertInstanceOf(Document::class, $node);
        $this->assertSame($expected, (string) $node);
    }

    public function testReadFullPage()
    {
        $node = $this->reader->read(
            new Stream(
                fopen('fixtures/lemonde.html', 'r')
            )
        );

        $this->assertInstanceOf(Document::class, $node);
    }

    public function testReadScreenOnline()
    {
        $node = $this->reader->read(new Stream(fopen(
            'fixtures/www.screenonline.org.uk_tv_id_560180_.html',
            'r'
        )));

        $this->assertInstanceOf(XmlDocument::class, $node);
    }
}
