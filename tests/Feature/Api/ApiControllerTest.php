<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class ApiControllerTest extends TestCase
{
    public function test_get_info(): void
    {

        $this->get(route('api.info'))
            ->assertJson([
                'version' => '1.0',
            ])
        ;
    }
}

