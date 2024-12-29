<?php

namespace Tests\Traits\Assert;

use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;

trait AssertValidations
{
    protected static function assertValidationError(
        TestResponse $result,
        string $field,
        string $validationMsg,
        string|null $key = null,
    ): void
    {
        self::assertEquals($result->status(), Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertEquals($result->json('msg'), $validationMsg);

        if(is_null($key)){
            $key = mb_strtolower($field);
        }

        self::assertTrue(
            in_array($validationMsg, $result->json('errors.'.$key))
        );
    }
}


