<?php

namespace Tests\Feature\Api\Tags\Public\Controller;

use App\Models\Tags\Tag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Tags\TagBuilder;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use DatabaseTransactions;

    protected TagBuilder $tagBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->tagBuilder = resolve(TagBuilder::class);
    }

    public function test_success_as_auth_user()
    {
        $this->loginAsAdmin();

        /** @var $model Tag */
        $model_1 = $this->tagBuilder
            ->public_attached(3)
            ->private_attached(4)
            ->create();
        $model_2 = $this->tagBuilder
            ->public_attached(23)
            ->private_attached(24)
            ->create();
        $model_3 = $this->tagBuilder
            ->public_attached(13)
            ->private_attached(14)
            ->create();

        $this->tagBuilder
            ->public_attached(1)
            ->private_attached(0)
            ->create();

        $this->getJson(route('api.tag.index'))
            ->assertJson([
                [
                    'id' => $model_2->id,
                    'attached' => 24,
                ],
                [
                    'id' => $model_3->id,
                    'attached' => 14,
                ],
                [
                    'id' => $model_1->id,
                    'attached' => 4,
                ],
            ])
            ->assertJsonCount(3)
            ->assertValidResponse(200)
        ;
    }

    public function test_success_as_guest_user()
    {
        /** @var $model Tag */
        $model_1 = $this->tagBuilder
            ->public_attached(3)
            ->private_attached(4)
            ->create();
        $model_2 = $this->tagBuilder
            ->public_attached(23)
            ->private_attached(24)
            ->create();
        $model_3 = $this->tagBuilder
            ->public_attached(13)
            ->private_attached(14)
            ->create();

        $this->tagBuilder
            ->public_attached(0)
            ->private_attached(1)
            ->create();

        $this->getJson(route('api.tag.index'))
            ->assertJson([
                [
                    'id' => $model_2->id,
                    'attached' => 23,
                ],
                [
                    'id' => $model_3->id,
                    'attached' => 13,
                ],
                [
                    'id' => $model_1->id,
                    'attached' => 3,
                ],
            ])
            ->assertJsonCount(3)
            ->assertValidResponse(200)
        ;
    }

    public function test_success_empty_data()
    {
        $this->loginAsAdmin();

        $this->getJson(route('api.tag.index'))
            ->assertJson([])
            ->assertJsonCount(0)
        ;
    }

    public function test_search()
    {
        $this->loginAsAdmin();

        /** @var $model Tag */
        $model_1 = $this->tagBuilder
            ->name('aabbaa')
            ->private_attached(4)
            ->create();
        $model_2 = $this->tagBuilder
            ->name('bbbbb')
            ->private_attached(4)
            ->create();
        $model_3 = $this->tagBuilder
            ->name('zzzzz')
            ->private_attached(4)
            ->create();

        $this->getJson(route('api.tag.index',[
            'search' => 'bb'
        ]))
            ->assertJson([
                ['id' => $model_1->id,],
                ['id' => $model_2->id,],
            ])
            ->assertJsonCount(2)
        ;
    }
}
