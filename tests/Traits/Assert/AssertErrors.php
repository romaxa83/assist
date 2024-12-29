<?php

namespace Tests\Traits\Assert;

use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;

trait AssertErrors
{
    protected static function assertUnauthorized(
        TestResponse $result
    ): void
    {
        self::assertEquals($result->json('msg'), __('Unauthorized'));
        self::assertEquals($result->status(), Response::HTTP_UNAUTHORIZED);
    }

    protected static function assertNotFound(
        TestResponse $result
    ): void
    {
        self::assertEquals($result->json('msg'), __('Not Found'));
        self::assertEquals($result->status(), Response::HTTP_NOT_FOUND);
    }
}


