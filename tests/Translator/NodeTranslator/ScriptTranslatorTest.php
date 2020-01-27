<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\ScriptTranslator,
    Element\Script,
    Exception\InvalidArgumentException,
};
use Innmind\Xml\Translator\{
    Translator,
    NodeTranslators,
    NodeTranslator,
};
use PHPUnit\Framework\TestCase;

class ScriptTranslatorTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslator::class,
            new ScriptTranslator
        );
    }

    public function testThrowWhenNotExpectedElement()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<body></body>');

        $this->expectException(InvalidArgumentException::class);

        (new ScriptTranslator)(
            $dom->childNodes->item(1),
            new Translator(
                NodeTranslators::defaults()
            )
        );
    }

    public function testTranslate()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<script type="text/javascript">var foo = 42;</script>');

        $script = (new ScriptTranslator)(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            new Translator(
                NodeTranslators::defaults()
            )
        );

        $this->assertInstanceOf(Script::class, $script);
        $this->assertSame('var foo = 42;', $script->content());
        $this->assertCount(1, $script->attributes());
        $this->assertSame(
            'text/javascript',
            $script->attributes()->get('type')->value()
        );
        $this->assertCount(1, $script->children());
    }

    public function testTranslateWithoutCode()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<script></script>');

        $script = (new ScriptTranslator)(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            new Translator(
                NodeTranslators::defaults()
            )
        );

        $this->assertInstanceOf(Script::class, $script);
        $this->assertSame('', $script->content());
        $this->assertCount(0, $script->attributes());
        $this->assertCount(1, $script->children());
    }
}
