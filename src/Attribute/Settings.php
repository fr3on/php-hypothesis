<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Attribute;

use Attribute;

/**
 * Attribute to configure the Hypothesis engine for a specific test.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
final readonly class Settings
{
    /**
     * @param array<int, string> $suppressHealthCheck
     */
    public function __construct(
        public int $maxExamples = 100,
        public int $deadline = 200, // ms
        public array $suppressHealthCheck = []
    ) {
    }
}
