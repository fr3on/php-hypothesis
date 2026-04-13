<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Shape;

use Fr3on\Hypothesis\Engine\Randomness;
use Fr3on\Hypothesis\Shrink\Shrinker;
use Fr3on\Hypothesis\Shrink\ListShrinker;

/**
 * Shape for generating lists (indexed arrays).
 * 
 * @template TElem
 * @implements ShapeInterface<array<TElem>>
 */
final readonly class ListShape implements ShapeInterface
{
    /**
     * @param ShapeInterface<TElem> $elementShape
     */
    public function __construct(
        private ShapeInterface $elementShape,
        private int $minSize = 0,
        private int $maxSize = 100
    ) {
    }

    /**
     * @return array<TElem>
     */
    public function generate(Randomness $random): array
    {
        $size = $random->drawInt($this->minSize, $this->maxSize);
        $list = [];

        for ($i = 0; $i < $size; $i++) {
            $list[] = $this->elementShape->generate($random);
        }

        return $list;
    }

    /**
     * @return Shrinker<array<TElem>>
     */
    public function getShrinker(): Shrinker
    {
        return new ListShrinker($this->elementShape->getShrinker());
    }
}
