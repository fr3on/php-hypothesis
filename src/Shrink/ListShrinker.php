<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Shrink;

/**
 * Shrinker for lists (indexed arrays). 
 * 
 * Strategy:
 * 1. Try to reduce the length of the list (binary search on length).
 * 2. Try to shrink individual elements.
 * 
 * @template T
 * @implements Shrinker<array<T>>
 */
class ListShrinker implements Shrinker
{
    /**
     * @param Shrinker<T> $elementShrinker
     */
    public function __construct(
        private readonly Shrinker $elementShrinker
    ) {}

    /**
     * @param array<T> $value
     * @param callable(array<T>): bool $isStillFailing
     * @return array<T>
     */
    public function shrink(mixed $value, callable $isStillFailing): array
    {
        $currentFailing = $value;

        // 1. Try to shrink by length (binary search removal)
        // This is a simplified version of Hypothesis's internal shrinker
        $currentFailing = $this->shrinkLength($currentFailing, $isStillFailing);

        // 2. Try to shrink elements
        $currentFailing = $this->shrinkElements($currentFailing, $isStillFailing);

        return $currentFailing;
    }

    /**
     * @param array<T> $list
     * @param callable(array<T>): bool $isStillFailing
     * @return array<T>
     */
    private function shrinkLength(array $list, callable $isStillFailing): array
    {
        $current = $list;
        $idx = 0;

        while ($idx < count($current)) {
            $candidate = $current;
            array_splice($candidate, $idx, 1);

            if ($isStillFailing($candidate)) {
                $current = $candidate;
                // Don't increment idx, try to remove at the same position again
            } else {
                $idx++;
            }
        }

        return $current;
    }

    /**
     * @param array<T> $list
     * @param callable(array<T>): bool $isStillFailing
     * @return array<T>
     */
    private function shrinkElements(array $list, callable $isStillFailing): array
    {
        $current = $list;

        for ($i = 0; $i < count($current); $i++) {
            $current[$i] = $this->elementShrinker->shrink(
                $current[$i],
                function ($shrunkElement) use ($current, $i, $isStillFailing) {
                    $candidate = $current;
                    $candidate[$i] = $shrunkElement;
                    return $isStillFailing($candidate);
                }
            );
        }

        return $current;
    }
}
