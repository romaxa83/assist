<?php

namespace Tests\Feature\Api\Auth\Controller;

use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Users\UserBuilder;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    protected UserBuilder $userBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->userBuilder = resolve(UserBuilder::class);
    }

    public function test_success_login()
    {
        $password = 'Password123';
        /** @var $model User */
        $model = $this->userBuilder
            ->password($password)->create();

        $data = [
            'email' => $model->email,
            'password' => $password,
        ];

        $this->postJson(route('api.login'), $data)
            ->assertJsonStructure([
                'data' => [
                    'token',
                ]
            ])
        ;
    }

    public function test_success_login_uncorrected_email()
    {
        $password = 'Password123';
        $email = 'test@gmail.com';

        $this->userBuilder
            ->email($email)
            ->password($password)
            ->create()
        ;

        $data = [
            'email' => 'Test@gmail.com',
            'password' => $password,
        ];

        $this->postJson(route('api.login'), $data)
            ->assertJsonStructure([
                'data' => [
                    'token',
                ]
            ])
        ;
    }

    public function test_fail_wrong_password(): void
    {
        $password = 'Password123';
        /** @var $model User */
        $model = $this->userBuilder->password($password)->create();

        $data = [
            'email' => $model->email,
            'password' =>  $password . '4',
        ];

        $res = $this->postJson(route('api.login'), $data)
        ;

        self::assertUnauthorizedMsg($res);
    }

    public function test_fail_wrong_email(): void
    {
        $password = 'Password123';
        /** @var $model User */
        $model = $this->userBuilder->password($password)->create();

        $data = [
            'email' => $model->email . 'r',
            'password' => $password,
        ];

        $res = $this->postJson(route('api.login'), $data)
        ;

        self::assertUnauthorizedMsg($res);
    }
}
