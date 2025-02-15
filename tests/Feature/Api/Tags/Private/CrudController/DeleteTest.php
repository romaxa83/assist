<?php

namespace Tests\Feature\Api\Tags\Private\CrudController;

use App\Models\Tags\Tag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Tags\TagBuilder;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use DatabaseTransactions;

    protected TagBuilder $tagBuilder;

    public function setUp(): void
    {
        $this->tagBuilder = resolve(TagBuilder::class);

        parent::setUp();
    }

    public function test_success()
    {
        $this->loginAsAdmin();

        /** @var $model Tag */
        $model = $this->tagBuilder->create();

        $id = $model->id;

        $this->deleteJson(route('api.private.tag.delete', ['id' => $model->id]))
            ->assertValidResponse(204)
        ;

        $this->assertNull(Tag::find($id));
    }


    public function test_fail_not_found_model()
    {
        $this->loginAsAdmin();

        /** @var $model Tag */
        $model = $this->tagBuilder->create();


        $res = $this->deleteJson(route('api.private.tag.delete', ['id' => $model->id +1]))
        ;

        self::assertNotFound($res);
    }

    public function test_not_auth()
    {
        /** @var $model Tag */
        $model = $this->tagBuilder->create();

        $res = $this->deleteJson(route('api.private.tag.delete', ['id' => $model->id]))
        ;

        self::assertUnauthorized($res);
    }
}
