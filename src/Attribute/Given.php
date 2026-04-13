<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Attribute;

use Attribute;
use Fr3on\Hypothesis\Shape\ShapeInterface;

/**
 * Marks a test method as a property test and defines its input shapes.
 */
#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Given
{
    /** @var array<ShapeInterface<mixed>> */
    public array $shapes;

    public function __construct(ShapeInterface ...$shapes)
    {
        $this->shapes = $shapes;
    }
}

/**
 * Configure settings for a property test.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
final readonly class Settings
{
    public function __construct(
        public int $maxExamples = 100,
        public int $deadline = 200, // ms
        public array $suppressHealthCheck = []
    ) {}
}
