<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\Assert\AssertErrors;

abstract class TestCase extends BaseTestCase
{
    use AssertErrors;
}
