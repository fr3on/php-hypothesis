<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Shape;

use Fr3on\Hypothesis\Engine\Randomness;
use Fr3on\Hypothesis\Shrink\Shrinker;
use Fr3on\Hypothesis\Shrink\IntegerShrinker;

/**
 * Shape for generating integers.
 * 
 * @implements ShapeInterface<int>
 */
final readonly class IntegerShape implements ShapeInterface
{
    public function __construct(
        private int $min = -1000000,
        private int $max = 1000000
    ) {
    }

    public function generate(Randomness $random): int
    {
        return $random->drawInt($this->min, $this->max);
    }

    public function getShrinker(): Shrinker
    {
        return new IntegerShrinker();
    }
}
