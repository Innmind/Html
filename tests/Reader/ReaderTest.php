<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Reader;

use Innmind\Html\{
    Reader\Reader,
    Translator\NodeTranslators as HtmlTranslators,
    Node\Document,
    Exception\RuntimeException,
};
use Innmind\Xml\{
    Reader as ReaderInterface,
    Translator\Translator,
    Translator\NodeTranslators,
    Node\Document as XmlDocument,
};
use Innmind\Stream\Readable\Stream;
use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{
    private $read;

    public function setUp(): void
    {
        $this->read = Reader::of(
            new Translator(
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
            $this->read
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
        $node = ($this->read)(
            Stream::ofContent($html),
        );
        $expected = <<<HTML
<!DOCTYPE html>
<html><head/><body>
        foo
    </body></html>
HTML;

        if (\PHP_OS === 'Darwin') {
            // don't know why there is a difference between linux and macOS
            $expected = <<<HTML
            <!DOCTYPE html>
            <html>
                <head/>
                <body>
                    foo
                </body>
            </html>
            HTML;
        }

        $this->assertInstanceOf(Document::class, $node);
        $this->assertSame($expected, $node->toString());
    }

    public function testReadFullPage()
    {
        $node = ($this->read)(
            new Stream(
                \fopen('fixtures/lemonde.html', 'r')
            )
        );

        $this->assertInstanceOf(Document::class, $node);
    }

    public function testReadScreenOnline()
    {
        $node = ($this->read)(new Stream(\fopen(
            'fixtures/www.screenonline.org.uk_tv_id_560180_.html',
            'r'
        )));

        $this->assertInstanceOf(XmlDocument::class, $node);
    }

    public function testThrowWhenEmptyStream()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No html found');

        ($this->read)(Stream::ofContent(''));
    }
}
