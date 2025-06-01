<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\Script;
use Innmind\Xml\{
    Element,
    Node\Text,
    Attribute,
};
use Innmind\Immutable\Set;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ScriptTest extends TestCase
{
    public function testInterface()
    {
        $script = Script::of(
            Text::of('foo'),
        );

        $this->assertInstanceOf(Element::class, $script);
        $this->assertSame('<script>foo</script>', $script->toString());
        $this->assertCount(1, $script->children());
    }

    public function testWithAttributes()
    {
        $script = Script::of(
            Text::of('foo'),
            Set::of(
                $attribute = Attribute::of('foo', 'bar'),
            ),
        );

        $this->assertSame($attribute, $script->attributes()->get('foo')->match(
            static fn($attribute) => $attribute,
            static fn() => null,
        ));
    }
}
