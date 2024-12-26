<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\Assert\AssertErrors;
use Tests\Traits\InteractsWithAuth;

abstract class TestCase extends BaseTestCase
{
    use AssertErrors;
    use InteractsWithAuth;
}
