<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Attribute;

use Attribute;
use Fr3on\Hypothesis\Shape\ShapeInterface;

/**
 * Attribute to define data generators for test parameters.
 */
#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Given
{
    /** @var array<int, ShapeInterface<mixed>> */
    public array $shapes;

    /**
     * @param ShapeInterface<mixed> ...$shapes
     */
    public function __construct(ShapeInterface ...$shapes)
    {
        $this->shapes = array_values($shapes);
    }
}
