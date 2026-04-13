<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Shape;

use Fr3on\Hypothesis\Engine\Randomness;
use Fr3on\Hypothesis\Shrink\Shrinker;
use Fr3on\Hypothesis\Shrink\StringShrinker;

/**
 * Shape for generating strings.
 * 
 * @implements ShapeInterface<string>
 */
final readonly class StringShape implements ShapeInterface
{
    private string $alphabet;

    public function __construct(
        private int $minLength = 0,
        private int $maxLength = 100,
        ?string $alphabet = null
    ) {
        $this->alphabet = $alphabet ?? 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 ';
        
        if ($this->minLength > $this->maxLength) {
            throw new \InvalidArgumentException("MinLength cannot be greater than MaxLength.");
        }
    }

    public function generate(Randomness $random): string
    {
        $length = $random->drawInt($this->minLength, $this->maxLength);
        $s = '';
        $chars = str_split($this->alphabet);

        for ($i = 0; $i < $length; $i++) {
            $s .= $random->choose($chars);
        }

        return $s;
    }

    public function getShrinker(): Shrinker
    {
        return new StringShrinker();
    }
}
