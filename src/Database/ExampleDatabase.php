<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Database;

/**
 * Interface for storing and retrieving failing examples.
 */
interface ExampleDatabase
{
    /**
     * @param string $key Unique key for the test property
     * @param array<mixed> $example The minimal failing example
     */
    public function save(string $key, array $example): void;

    /**
     * @param string $key Unique key for the test property
     * @return array<mixed>|null The failing example if found
     */
    public function load(string $key): ?array;
}
