<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Tests\Unit;

use Fr3on\Hypothesis\PHPUnit\PropertyTestCase;
use Fr3on\Hypothesis\Attribute\Given;
use Fr3on\Hypothesis\Shape\Shape;

use Fr3on\Hypothesis\Shape\IntegerShape;
use Fr3on\Hypothesis\Shape\ListShape;

class SortingTest extends PropertyTestCase
{
    #[Given(new ListShape(new IntegerShape()))]
    public function prop_sorted_list_has_same_length(array $xs): void
    {
        $sorted = $xs;
        sort($sorted);
        $this->assertCount(count($xs), $sorted);
    }

    #[Given(
        new IntegerShape(1, 1000),
        new IntegerShape(1, 1000)
    )]
    public function prop_addition_is_commutative(int $a, int $b): void
    {
        $this->assertSame($a + $b, $b + $a);
    }
}
