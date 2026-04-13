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
     * Overriding runTest to handle property test execution.
     */
    protected function runTest(): mixed
    {
        $this->runPropertyTest();
        return null;
    }
}
