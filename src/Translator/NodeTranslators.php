<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator;

use Innmind\Html\Translator\NodeTranslator\{
    DocumentTranslator,
    ElementTranslator
};
use Innmind\Xml\Translator\{
    NodeTranslatorInterface,
    NodeTranslator\ElementTranslator as GenericTranslator
};
use Innmind\Immutable\{
    Map,
    MapInterface
};

final class NodeTranslators
{
    private static $defaults;

    /**
     * @return MapInterface<int, NodeTranslatorInterface>
     */
    public static function defaults(): MapInterface
    {
        if (!self::$defaults) {
            self::$defaults = (new Map('int', NodeTranslatorInterface::class))
                ->put(XML_HTML_DOCUMENT_NODE, new DocumentTranslator)
                ->put(
                    XML_ELEMENT_NODE,
                    new ElementTranslator(
                        new GenericTranslator,
                        new Map('string', NodeTranslatorInterface::class)
                    )
                );
        }

        return self::$defaults;
    }
}
