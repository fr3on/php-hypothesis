<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\PHPUnit;

use Fr3on\Hypothesis\Attribute\Given;
use Fr3on\Hypothesis\Attribute\Settings;
use Fr3on\Hypothesis\Engine\Runner;
use ReflectionMethod;

/**
 * Trait to be used in PHPUnit TestCase classes to enable property-based testing.
 */
trait HypothesisTrait
{
    /**
     * This is an internal method that should be called by the PHPUnit Extension or manually.
     * It scans for #[Given] attribute and runs the property test.
     */
    protected function runPropertyTest(): void
    {
        $method = $this->name();
        $reflection = new \ReflectionMethod($this, $method);
        
        $given = ($reflection->getAttributes(Given::class)[0] ?? null)?->newInstance();
        if (!$given instanceof Given) {
            return;
        }

        $settings = ($reflection->getAttributes(Settings::class)[0] ?? null)?->newInstance() 
            ?? new Settings();

        $runner = new Runner(maxExamples: $settings->maxExamples);
        
        // Define the test closure. 
        // We use $this to allow access to PHPUnit assertions.
        $testClosure = function (mixed ...$args) use ($reflection) {
            $reflection->invoke($this, ...$args);
        };

        $testId = get_class($this) . '::' . $method;

        $runner->run($testId, $given->shapes, $testClosure);
    }
}
