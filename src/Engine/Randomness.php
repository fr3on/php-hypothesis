<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Engine;

use Random\Engine\Mt19937;
use Random\Randomizer;

/**
 * Seeded randomness provider to ensure reproducibility of property tests.
 */
class Randomness
{
    private Randomizer $randomizer;
    private int $seed;

    public function __construct(?int $seed = null)
    {
        $this->seed = $seed ?? random_int(0, PHP_INT_MAX);
        $this->randomizer = new Randomizer(new Mt19937($this->seed));
    }

    /**
     * Returns the seed used for this instance.
     */
    public function getSeed(): int
    {
        return $this->seed;
    }

    /**
     * Re-initializes the engine with a specific seed.
     */
    public function reseed(int $seed): void
    {
        $this->seed = $seed;
        $this->randomizer = new Randomizer(new Mt19937($this->seed));
    }

    /**
     * Draws a random integer between min and max.
     */
    public function drawInt(int $min, int $max): int
    {
        return $this->randomizer->getInt($min, $max);
    }

    /**
     * Draws a random float between 0 and 1.
     */
    public function drawFloat(): float
    {
        return $this->randomizer->getFloat(0, 1);
    }

    /**
     * Picks a random element from an array.
     * 
     * @template T
     * @param array<T> $elements
     * @return T
     */
    public function choose(array $elements): mixed
    {
        if (empty($elements)) {
            throw new \InvalidArgumentException("Cannot choose from an empty array.");
        }

        $index = $this->drawInt(0, count($elements) - 1);
        $values = array_values($elements);
        
        return $values[$index];
    }

    /**
     * Returns true with a given probability.
     */
    public function chance(float $probability): bool
    {
        return $this->drawFloat() < $probability;
    }
}
