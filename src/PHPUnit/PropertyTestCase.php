<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\PHPUnit;

use PHPUnit\Framework\TestCase;

/**
 * Base class for property-based tests.
 */
abstract class PropertyTestCase extends TestCase
{
    use HypothesisTrait;

    /**
     * Gateway test that runs all property methods discovered by the provider.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('hypothesisMethodProvider')]
    public function run_hypothesis_property(string $method): void
    {
        $this->runPropertyMethod($method);
    }
}
