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
    private static $defaults;

    /**
     * @return MapInterface<int, NodeTranslator>
     */
    public static function defaults(): MapInterface
    {
        if (!self::$defaults) {
            self::$defaults = (new Map('int', NodeTranslator::class))
                ->put(XML_HTML_DOCUMENT_NODE, new DocumentTranslator)
                ->put(
                    XML_ELEMENT_NODE,
                    new ElementTranslator(
                        new GenericTranslator,
                        (new Map('string', NodeTranslator::class))
                            ->put('a', new ATranslator)
                            ->put('base', new BaseTranslator)
                            ->put('img', new ImgTranslator)
                            ->put('link', new LinkTranslator)
                            ->put('script', new ScriptTranslator)
                    )
                );
        }

        return self::$defaults;
    }
}
