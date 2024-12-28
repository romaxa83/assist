<?php

namespace Tests\Feature\Api\Tags\CrudController;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;

    protected array $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->data = [
            'name' => 'test',
            'color' => '#d98b84',
        ];
    }

    public function test_success()
    {
        $this->loginAsAdmin();

        $data = $this->data;

        $this->postJson(route('api.tag.create'), $data)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',
                    'color',
                ]
            ])
        ;
    }

    public function test_fail_not_auth()
    {
        $data = $this->data;

        $res = $this->postJson(route('api.tag.create'), $data)
        ;

        self::assertUnauthorizedMsg($res);
    }
}
