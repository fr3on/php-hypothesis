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
     * Executes the hypothesis engine for a specific method.
     */
    protected function runPropertyMethod(string $method): void
    {
        $reflection = new ReflectionMethod($this, $method);
        
        $given = ($reflection->getAttributes(Given::class)[0] ?? null)?->newInstance();
        if (!$given instanceof Given) {
            return;
        }

        $settings = ($reflection->getAttributes(Settings::class)[0] ?? null)?->newInstance() 
            ?? new Settings();

        $runner = new Runner(maxExamples: $settings->maxExamples);
        
        $obj = $this;
        $testClosure = function (mixed ...$args) use ($reflection, $obj) {
            $reflection->invoke($obj, ...$args);
        };

        $testId = get_class($this) . '::' . $method;

        $runner->run($testId, $given->shapes, $testClosure);
    }

    /**
     * Finds all methods in the current class that have the #[Given] attribute.
     * 
     * @return array<string, array{string}>
     */
    public static function hypothesisMethodProvider(): array
    {
        $methods = [];
        $reflection = new \ReflectionClass(static::class);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (!empty($method->getAttributes(Given::class))) {
                $methods[$method->getName()] = [$method->getName()];
            }
        }

        return $methods;
    }
}
