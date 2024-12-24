<?php

namespace Tests\Unit\Helpers\Functions;

use Tests\TestCase;

class JsonToArrayTest extends TestCase
{
    public function test_success(): void
    {
        $json = "{\"test\": 5}";

        $this->assertEquals([
            "test" => 5
        ],json_to_array($json));
    }

    public function test_success_multi_array(): void
    {
        $json = "{\"test\": {\"test\": 2}}";

        $this->assertEquals([
            "test" => ["test" => 2]
        ],json_to_array($json));
    }

    public function test_empty_json(): void
    {
        $json = "{}";

        $this->assertEquals([],json_to_array($json));
    }

    public function test_empty_string(): void
    {
        $json = '';

        $this->assertEquals([],json_to_array($json));

        $json = ' ';

        $this->assertEquals([],json_to_array($json));
    }
}
