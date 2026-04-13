<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\PHPUnit;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

/**
 * PHPUnit 11 Extension for Hypothesis.
 */
class HypothesisExtension implements Extension
{
    public function bootstrap(
        Configuration $configuration,
        Facade $facade,
        ParameterCollection $parameters
    ): void {
        // In a full implementation, we would register subscribers here
        // to handle global initialization (e.g., clearing the database).
    }
}
