<?php

namespace Tests\Feature\Api\Users\ProfileController;

use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Users\UserBuilder;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseTransactions;

    protected UserBuilder $userBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->userBuilder = resolve(UserBuilder::class);
    }

    public function test_success_get_current_user()
    {
        /** @var $model User */
        $model = $this->loginAsAdmin();

        $this->getJson(route('api.profile'))
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $model->id,
                ]
            ])
        ;
    }

    public function test_fail_get_current_user()
    {
        $res = $this->getJson(route('api.profile'))
        ;

        self::assertUnauthorizedMsg($res);
    }
}
