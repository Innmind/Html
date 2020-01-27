<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\Script;
use Innmind\Xml\{
    Element\Element,
    Node\Text,
    Attribute,
};
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class ScriptTest extends TestCase
{
    public function testInterface()
    {
        $script = new Script(
            new Text('foo')
        );

        $this->assertInstanceOf(Element::class, $script);
        $this->assertSame('<script>foo</script>', $script->toString());
        $this->assertCount(1, $script->children());
    }

    public function testWithAttributes()
    {
        $script = new Script(
            new Text('foo'),
            Set::of(
                Attribute::class,
                $attribute = new Attribute('foo', 'bar'),
            ),
        );

        $this->assertSame($attribute, $script->attributes()->get('foo'));
    }
}
