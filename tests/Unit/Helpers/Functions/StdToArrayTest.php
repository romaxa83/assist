<?php

namespace Tests\Unit\Helpers\Functions;

use Tests\TestCase;

class StdToArrayTest extends TestCase
{
    public function test_success(): void
    {
        $std = new \stdClass();
        $std->test = 5;

        $this->assertEquals([
            "test" => 5
        ], std_to_array($std));
    }

    public function test_success_multi_array(): void
    {
        $std = new \stdClass();
        $std_1 = new \stdClass();
        $std_1->test = 5;

        $std->test = $std_1;

        $this->assertEquals([
            "test" => ["test" => 5]
        ], std_to_array($std));
    }

    public function test_empty_std(): void
    {
        $std = new \stdClass();

        $this->assertEquals([], std_to_array($std));
    }
}
