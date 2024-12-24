<?php

namespace Tests\Unit\Helpers\Functions;

use Tests\TestCase;

class PhoneClearTest extends TestCase
{
    public function test_phone_clear(): void
    {
        $phone = '+38(095) 450-00-11';

        $this->assertEquals('380954500011', phone_clear($phone));
    }
}
