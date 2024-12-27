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
            ->dump()
            ->assertJson([
                'success' => true,
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
