<?php

namespace Tests\Unit\Helpers\Functions;

use Tests\TestCase;

class ArrayToJsonTest extends TestCase
{
    public function test_success(): void
    {
        $arr = ["test" => 4];

        $this->assertEquals("{\"test\":4}", array_to_json($arr));
    }

    public function test_success_multi_array(): void
    {
        $arr = ["test" => ['test' => 6]];

        $this->assertEquals('{"test":{"test":6}}', array_to_json($arr));
    }

    public function test_empty_json(): void
    {
        $arr = [];

        $this->assertEquals('[]', array_to_json($arr));
    }
}
