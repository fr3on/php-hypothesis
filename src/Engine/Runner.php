<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Engine;

use Fr3on\Hypothesis\Shape\ShapeInterface;
use Fr3on\Hypothesis\Database\ExampleDatabase;
use Fr3on\Hypothesis\Database\FilesystemDatabase;

/**
 * Orchestrates the property-based testing cycle.
 */
class Runner
{
    private Randomness $random;
    private ExampleDatabase $database;

    public function __construct(
        private int $maxExamples = 100,
        ?int $seed = null,
        ?ExampleDatabase $database = null
    ) {
        $this->random = new Randomness($seed);
        $this->database = $database ?? new FilesystemDatabase();
    }

    /**
     * @param array<int, ShapeInterface<mixed>> $shapes
     */
    public function run(string $testId, array $shapes, callable $test): void
    {
        // 0. Try replaying from database first
        $this->tryReplay($testId, $shapes, $test);

        for ($i = 0; $i < $this->maxExamples; $i++) {
            // 1. Generate inputs
            $inputs = array_map(fn($s) => $s->generate($this->random), $shapes);

            // 2. Run test
            try {
                $test(...$inputs);
            } catch (\Throwable $e) {
                // 3. If fails, shrink!
                $this->shrinkAndReport($testId, $shapes, $inputs, $test, $e);
            }
        }
    }

    /**
     * @param array<int, ShapeInterface<mixed>> $shapes
     */
    private function tryReplay(string $testId, array $shapes, callable $test): void
    {
        $example = $this->database->load($testId);
        if ($example === null) {
            return;
        }

        try {
            $test(...$example);
        } catch (\Throwable $e) {
            // If it still fails, we might want to shrink it again (it might be even simpler now)
            $this->shrinkAndReport($testId, $shapes, $example, $test, $e);
        }
    }

    /**
     * @param array<int, ShapeInterface<mixed>> $shapes
     * @param array<int, mixed> $failingInputs
     */
    private function shrinkAndReport(string $testId, array $shapes, array $failingInputs, callable $test, \Throwable $originalError): void
    {
        $currentFailing = $failingInputs;

        for ($i = 0; $i < count($shapes); $i++) {
            $shrinker = $shapes[$i]->getShrinker();
            
            $currentFailing[$i] = $shrinker->shrink(
                $currentFailing[$i],
                function (mixed $sv) use ($currentFailing, $i, $test) {
                    $candidate = $currentFailing;
                    $candidate[$i] = $sv;
                    try {
                        $test(...$candidate);
                        return false; 
                    } catch (\Throwable) {
                        return true; 
                    }
                }
            );
        }

        // Save to database
        $this->database->save($testId, $currentFailing);

        throw new \RuntimeException(
            sprintf(
                "Falsified! Minimal failing case: %s\nSeed: %d\nOriginal error: %s",
                json_encode($currentFailing),
                $this->random->getSeed(),
                $originalError->getMessage()
            ),
            0,
            $originalError
        );
    }
}
