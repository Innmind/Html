<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator;

use Innmind\Html\Translator\NodeTranslator\{
    DocumentTranslator,
    ElementTranslator,
    ATranslator,
    BaseTranslator,
    ImgTranslator,
    LinkTranslator,
    ScriptTranslator,
};
use Innmind\Xml\Translator\{
    NodeTranslator,
    NodeTranslator\ElementTranslator as GenericTranslator,
};
use Innmind\Immutable\{
    MapInterface,
    Map,
};

final class NodeTranslators
{
    private static ?Map $defaults = null;

    /**
     * @return MapInterface<int, NodeTranslator>
     */
    public static function defaults(): MapInterface
    {
        return self::$defaults ??= Map::of('int', NodeTranslator::class)
            (XML_HTML_DOCUMENT_NODE, new DocumentTranslator)
            (
                XML_ELEMENT_NODE,
                new ElementTranslator(
                    new GenericTranslator,
                    Map::of('string', NodeTranslator::class)
                        ('a', new ATranslator)
                        ('base', new BaseTranslator)
                        ('img', new ImgTranslator)
                        ('link', new LinkTranslator)
                        ('script', new ScriptTranslator)
                )
            );
    }
}
