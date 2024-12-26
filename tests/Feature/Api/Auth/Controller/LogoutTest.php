<?php

namespace Tests\Feature\Api\Auth\Controller;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Users\UserBuilder;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use DatabaseTransactions;

    protected UserBuilder $userBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->userBuilder = resolve(UserBuilder::class);
    }

    public function test_success_logout()
    {
        $this->loginAsAdmin();

        $this->postJson(route('api.logout'))
            ->assertJson([
                'success' => true,
                'data' => 'Logout'
            ])
        ;
    }

    public function test_fail_logout_not_user()
    {
        $res = $this->postJson(route('api.logout'))
        ;

        self::assertUnauthorizedMsg($res);
    }
}
