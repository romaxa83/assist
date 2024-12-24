<?php

namespace Tests\Unit\Helpers\Functions;

use Tests\TestCase;

class IsInRangeTest extends TestCase
{
    public function test_in_range(): void
    {
        $number = 11;
        $min = 10;
        $max = 20;

        $this->assertTrue(is_in_range($number, $min, $max));
    }

    public function test_in_range_a_boarder(): void
    {
        $number = 10;
        $min = 10;
        $max = 20;

        $this->assertTrue(is_in_range($number, $min, $max));
    }

    public function test_in_range_false(): void
    {
        $number = 9;
        $min = 10;
        $max = 20;

        $this->assertFalse(is_in_range($number, $min, $max));
    }

    public function test_in_range_number_wrong(): void
    {
        $number = '10-4567';
        $min = 10;
        $max = 20;

        $this->assertFalse(is_in_range($number, $min, $max));
    }
}
