<?php
declare(strict_types = 1);

namespace Innmind\Html\Visitor;

use Innmind\Html\Node\Document;
use Innmind\Xml\{
    Node,
    Element as Model,
    Element\Custom,
};
use Innmind\Immutable\{
    Maybe,
    Sequence,
    Predicate\Instance,
};

/**
 * @psalm-immutable
 */
final class Element
{
    /**
     * @param non-empty-string $name
     */
    private function __construct(private string $name)
    {
    }

    /**
     * @return Maybe<Model|Custom>
     */
    public function __invoke(Document|Node|Model|Custom $node): Maybe
    {
        return match (true) {
            $node instanceof Document => $this->visitDocument($node),
            $node instanceof Node => $this->visitNode($node),
            $node instanceof Model => $this->visitElement($node),
            $node instanceof Custom => $this->visitCustom($node),
        };
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     */
    public static function of(string $name): self
    {
        return new self($name);
    }

    /**
     * @psalm-pure
     */
    public static function head(): self
    {
        return new self('head');
    }

    /**
     * @psalm-pure
     */
    public static function body(): self
    {
        return new self('body');
    }

    /**
     * @return Maybe<Model|Custom>
     */
    private function visitDocument(Document $document): Maybe
    {
        return $this->visit($document->children());
    }

    /**
     * @return Maybe<Model|Custom>
     */
    private function visitNode(Node $node): Maybe
    {
        /** @var Maybe<Model|Custom> */
        return Maybe::nothing();
    }

    /**
     * @return Maybe<Model|Custom>
     */
    private function visitElement(Model $element): Maybe
    {
        if ($element->name()->toString() === $this->name) {
            return Maybe::just($element);
        }

        return $this->visit($element->children());
    }

    /**
     * @return Maybe<Model|Custom>
     */
    private function visitCustom(Custom $element): Maybe
    {
        $normalized = $element->normalize();

        if ($normalized->name()->toString() === $this->name) {
            return Maybe::just($element);
        }

        return $this->visit($normalized->children());
    }

    /**
     * @param Sequence<Model|Custom|Node> $children
     *
     * @return Maybe<Model|Custom>
     */
    private function visit(Sequence $children): Maybe
    {
        /** @var Model|Custom|null */
        $found = null;

        $found = $children
            ->sink($found)
            ->until(
                fn($found, $child, $continuation) => $this($child)->match(
                    static fn($found) => $continuation->stop($found),
                    static fn() => $continuation->continue($found),
                ),
            );

        return Maybe::of($found)->keep(
            Instance::of(Model::class)->or(
                Instance::of(Custom::class),
            ),
        );
    }
}
