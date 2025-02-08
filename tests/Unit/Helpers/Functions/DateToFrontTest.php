<?php

namespace Tests\Unit\Helpers\Functions;

use Carbon\CarbonImmutable;
use Tests\TestCase;

class DateToFrontTest extends TestCase
{
    public function test_success(): void
    {
        $now = CarbonImmutable::now();

        $this->assertEquals($now->format(\App\Enums\DateFormat::FRONT()), date_to_front($now));
    }
}
