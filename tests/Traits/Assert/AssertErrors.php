<?php

namespace Tests\Traits\Assert;

use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;

trait AssertErrors
{
    protected static function assertUnauthorizedMsg(
        TestResponse $result
    ): void
    {
        self::assertFalse($result->json('success'));
        self::assertEquals($result->json('msg'), __('Unauthorized'));
        self::assertEquals($result->status(), Response::HTTP_UNAUTHORIZED);
    }
}


