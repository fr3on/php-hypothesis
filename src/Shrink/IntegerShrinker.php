<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Shrink;

/**
 * Shrinker for integers. Moves toward 0 or boundaries using binary search.
 * 
 * @implements Shrinker<int>
 */
class IntegerShrinker implements Shrinker
{
    /**
     * @param int $value
     * @param callable(int): bool $isStillFailing
     * @return int
     */
    public function shrink(mixed $value, callable $isStillFailing): int
    {
        // If 0 is already the value, we can't shrink further toward 0
        if ($value === 0) {
            return 0;
        }

        // Try 0 first as the simplest case
        if ($isStillFailing(0)) {
            return 0;
        }

        $currentFailing = $value;
        $target = 0;

        // Binary search between current failing and target (0)
        while (abs($currentFailing - $target) > 1) {
            $mid = $target + (int)(($currentFailing - $target) / 2);

            if ($isStillFailing($mid)) {
                $currentFailing = $mid;
            } else {
                $target = $mid;
            }
        }

        return $currentFailing;
    }
}
