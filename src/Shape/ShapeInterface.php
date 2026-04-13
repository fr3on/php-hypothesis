<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Shape;

use Fr3on\Hypothesis\Engine\Randomness;
use Fr3on\Hypothesis\Shrink\Shrinker;

/**
 * Interface for data generators (Shapes).
 * 
 * @template T
 */
interface ShapeInterface
{
    /**
     * Generates a random value using the provided randomness engine.
     * 
     * @return T
     */
    public function generate(Randomness $random): mixed;

    /**
     * Returns a shrinker capable of simplifying values produced by this shape.
     * 
     * @return Shrinker<T>
     */
    public function getShrinker(): Shrinker;
}

/**
 * Factory and registry for built-in shapes.
 */
final readonly class Shape
{
    /**
     * @return IntegerShape
     */
    public static function integers(?int $min = null, ?int $max = null): IntegerShape
    {
        return new IntegerShape($min, $max);
    }

    /**
     * @return BooleanShape
     */
    public static function booleans(): BooleanShape
    {
        return new BooleanShape();
    }

    /**
     * @return StringShape
     */
    public static function strings(int $minLength = 0, int $maxLength = 100, ?string $alphabet = null): StringShape
    {
        return new StringShape($minLength, $maxLength, $alphabet);
    }

    /**
     * @template TElem
     * @param ShapeInterface<TElem> $elementShape
     * @return ListShape<TElem>
     */
    public static function lists(ShapeInterface $elementShape, int $minSize = 0, int $maxSize = 100): ListShape
    {
        return new ListShape($elementShape, $minSize, $maxSize);
    }
}
