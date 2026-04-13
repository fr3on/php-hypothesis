<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Shape;

use Fr3on\Hypothesis\Engine\Randomness;
use Fr3on\Hypothesis\Shrink\Shrinker;

/**
 * Shape for generating booleans.
 * 
 * @implements ShapeInterface<bool>
 */
final readonly class BooleanShape implements ShapeInterface
{
    public function generate(Randomness $random): bool
    {
        return $random->chance(0.5);
    }

    public function getShrinker(): Shrinker
    {
        return new class implements Shrinker {
            public function shrink(mixed $value, callable $isStillFailing): bool
            {
                if ($value === true && $isStillFailing(false)) {
                    return false;
                }
                return (bool) $value;
            }
        };
    }
}
