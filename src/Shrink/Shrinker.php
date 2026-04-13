<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Shrink;

/**
 * Interface for simplifying failing test inputs.
 * 
 * @template T
 */
interface Shrinker
{
    /**
     * Attempts to find a simpler version of $value that still fails.
     * 
     * @param T $value The current failing value
     * @param callable(T): bool $isStillFailing Callback to check if a candidate still triggers the failure
     * @return T The simplest version of the value found
     */
    public function shrink(mixed $value, callable $isStillFailing): mixed;
}
