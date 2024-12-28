<?php

namespace Tests\Feature\Api\Tags\CrudController;

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

    public function test_success()
    {
        $this->loginAsAdmin();

        /** @var $model Tag */
        $model_1 = $this->tagBuilder->weight(3)->create();
        $model_2 = $this->tagBuilder->weight(1)->create();
        $model_3 = $this->tagBuilder->weight(13)->create();

        $this->getJson(route('api.tag.index'))
            ->assertJson([
                'data' => [
                    ['id' => $model_3->id,],
                    ['id' => $model_1->id,],
                    ['id' => $model_2->id,],
                ]
            ])
        ;
    }

    public function test_search()
    {
        $this->loginAsAdmin();

        /** @var $model Tag */
        $model_1 = $this->tagBuilder
            ->name('aabbaa')
            ->weight(3)
            ->create();
        $model_2 = $this->tagBuilder
            ->name('bbbbb')
            ->weight(1)
            ->create();
        $model_3 = $this->tagBuilder
            ->name('zzzzz')
            ->weight(13)
            ->create();

        $this->getJson(route('api.tag.index',[
            'search' => 'bb'
        ]))
            ->assertJson([
                'data' => [
                    ['id' => $model_1->id,],
                    ['id' => $model_2->id,],
                ]
            ])
            ->assertJsonCount(2, 'data')
        ;
    }

    public function test_fail_not_auth()
    {
        $this->tagBuilder->weight(3)->create();

        $res = $this->getJson(route('api.tag.index'))
        ;

        self::assertUnauthorizedMsg($res);
    }
}
