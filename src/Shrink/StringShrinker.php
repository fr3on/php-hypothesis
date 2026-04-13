<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Shrink;

/**
 * Shrinker for strings. 
 * 
 * Strategy:
 * 1. Try to reduce the length of the string.
 * 2. Try to simplify characters (e.g., replace with 'a' or '0').
 * 
 * @implements Shrinker<string>
 */
class StringShrinker implements Shrinker
{
    /**
     * @param string $value
     * @param callable(string): bool $isStillFailing
     * @return string
     */
    public function shrink(mixed $value, callable $isStillFailing): string
    {
        $current = $value;

        // 1. Try to reduce length
        $current = $this->shrinkLength($current, $isStillFailing);

        // 2. Try to simplify characters (very basic version)
        $current = $this->shrinkCharacters($current, $isStillFailing);

        return $current;
    }

    private function shrinkLength(string $s, callable $isStillFailing): string
    {
        $current = $s;

        while (strlen($current) > 0) {
            // Try removing from the end
            $candidate = substr($current, 0, -1);
            if ($isStillFailing($candidate)) {
                $current = $candidate;
            } else {
                break;
            }
        }

        return $current;
    }

    private function shrinkCharacters(string $s, callable $isStillFailing): string
    {
        $current = $s;
        $alphabet = 'abcdefghijklmnopqrstuvwxyz0123456789 ';

        for ($i = 0; $i < strlen($current); $i++) {
            // Try to replace current char with a "simpler" one
            if ($current[$i] === 'a') continue;

            if ($isStillFailing(substr_replace($current, 'a', $i, 1))) {
                $current[$i] = 'a';
            } elseif ($isStillFailing(substr_replace($current, ' ', $i, 1))) {
                $current[$i] = ' ';
            }
        }

        return $current;
    }
}
