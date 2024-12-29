<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spectator\Spectator;
use Tests\Traits\Assert\AssertErrors;
use Tests\Traits\Assert\AssertValidations;
use Tests\Traits\InteractsWithAuth;

abstract class TestCase extends BaseTestCase
{
    use AssertErrors;
    use AssertValidations;
    use InteractsWithAuth;

    protected function setUp(): void
    {
        parent::setUp();

        Spectator::using('api-docs.json');
    }
}
