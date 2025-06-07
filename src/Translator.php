<?php
declare(strict_types = 1);

namespace Innmind\Html;

use Innmind\Html\Element\{
    A,
    Base,
    Img,
    Link,
    Script,
};
use Innmind\Xml\{
    Element,
    Element\Custom,
    Node,
    Document\Type,
    Translator as XmlTranslator,
};
use Innmind\Validation\Is;
use Innmind\Url\Url;
use Innmind\Immutable\{
    Sequence,
    Maybe,
    Attempt,
    Predicate\Instance,
};

/**
 * @psalm-immutable
 */
final class Translator
{
    private function __construct(
        private XmlTranslator $translate,
    ) {
    }

    /**
     * @psalm-suppress UndefinedClass Since the package still supports PHP 8.2
     *
     * @return Attempt<Document|Element|Custom|Node>
     */
    public function __invoke(\DOMNode|\Dom\Node $node): Attempt
    {
        return $this
            ->buildDocument($node)
            ->recover(fn() => $this->child($node));
    }

    /**
     * @psalm-pure
     */
    public static function new(): self
    {
        return new self(XmlTranslator::of(
            self::custom(...),
        ));
    }

    /**
     * @psalm-suppress UndefinedClass Since the package still supports PHP 8.2
     *
     * @return Attempt<Element|Custom|Node>
     */
    private function child(\DOMNode|\Dom\Node $node): Attempt
    {
        /** @var Attempt<Element|Custom|Node> Psalm doesn't understand the filter */
        return ($this->translate)($node)
            ->either()
            ->filter(
                Instance::of(Element::class)
                    ->or(Instance::of(Custom::class))
                    ->or(Instance::of(Node::class)),
                static fn() => new \RuntimeException('Invalid document node'),
            )
            ->match(
                Attempt::result(...),
                Attempt::error(...),
            );
    }

    /**
     * @psalm-suppress UndefinedClass Since the package still supports PHP 8.2
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedMethodCall
     * @psalm-suppress UndefinedPropertyFetch
     *
     * @return Attempt<Document>
     */
    private function buildDocument(\DOMNode|\Dom\Node $node): Attempt
    {
        /** @var Sequence<Node|Element|Custom> */
        $children = Sequence::of();

        return Maybe::just($node)
            ->keep(
                Instance::of(\DOMDocument::class)->or(
                    Instance::of(\Dom\Document::class),
                ),
            )
            ->attempt(static fn() => new \RuntimeException('Not a document'))
            ->flatMap(
                fn($document) => Sequence::of(...\array_values(\iterator_to_array($document->childNodes)))
                    ->keep(
                        Instance::of(\DOMNode::class)->or(
                            Instance::of(\Dom\Node::class),
                        ),
                    )
                    ->exclude(static fn($child) => $child->nodeType === \XML_DOCUMENT_TYPE_NODE)
                    ->sink($children)
                    ->attempt(
                        fn($children, $child) => $this
                            ->child($child)
                            ->map($children),
                    )
                    ->map(static fn($children) => Document::of(
                        Maybe::of($document->doctype)
                            ->flatMap(self::buildDoctype(...))
                            ->match(
                                static fn($type) => $type,
                                static fn() => Type::of('html'),
                            ),
                        $children,
                    )),
            );
    }

    /**
     * @psalm-pure
     * @psalm-suppress ImpurePropertyFetch
     * @psalm-suppress UndefinedClass Since the package still supports PHP 8.2
     *
     * @return Maybe<Type>
     */
    private static function buildDoctype(\DOMDocumentType|\Dom\DocumentType $type): Maybe
    {
        /** @psalm-suppress MixedArgument */
        return Type::maybe(
            $type->name,
            $type->publicId,
            $type->systemId,
        );
    }

    /**
     * @psalm-pure
     *
     * @return Maybe<Custom>
     */
    private static function custom(Element $element): Maybe
    {
        if ($element->name()->toString() === 'a') {
            return $element
                ->attribute('href')
                ->map(static fn($attribute) => $attribute->value())
                ->flatMap(Url::maybe(...))
                ->map(static fn($href) => A::of(
                    $href,
                    $element->attributes()->exclude(
                        static fn($attribute) => $attribute->name() === 'href',
                    ),
                    $element->children(),
                ));
        }

        if ($element->name()->toString() === 'base') {
            return $element
                ->attribute('href')
                ->map(static fn($attribute) => $attribute->value())
                ->flatMap(Url::maybe(...))
                ->map(static fn($href) => Base::of(
                    $href,
                    $element->attributes()->exclude(
                        static fn($attribute) => $attribute->name() === 'href',
                    ),
                ));
        }

        if ($element->name()->toString() === 'img') {
            return $element
                ->attribute('src')
                ->map(static fn($attribute) => $attribute->value())
                ->flatMap(Url::maybe(...))
                ->map(static fn($src) => Img::of(
                    $src,
                    $element->attributes()->exclude(
                        static fn($attribute) => $attribute->name() === 'src',
                    ),
                ));
        }

        if ($element->name()->toString() === 'link') {
            return $element
                ->attribute('href')
                ->map(static fn($attribute) => $attribute->value())
                ->flatMap(Url::maybe(...))
                ->map(static fn($href) => Link::of(
                    $href,
                    $element
                        ->attribute('rel')
                        ->map(static fn($attribute) => $attribute->value())
                        ->keep(Is::string()->nonEmpty()->asPredicate())
                        ->match(
                            static fn($rel) => $rel,
                            static fn() => 'related',
                        ),
                    $element->attributes()->exclude(
                        static fn($attribute) => \in_array(
                            $attribute->name(),
                            ['href', 'rel'],
                            true,
                        ),
                    ),
                ));
        }

        if ($element->name()->toString() === 'script') {
            return Maybe::just(Script::of(
                $element
                    ->children()
                    ->first()
                    ->keep(Instance::of(Node::class))
                    ->match(
                        static fn($node) => Node::text($node->content()),
                        static fn() => Node::text(''),
                    ),
                $element->attributes(),
            ));
        }

        /** @var Maybe<Custom> */
        return Maybe::nothing();
    }
}
