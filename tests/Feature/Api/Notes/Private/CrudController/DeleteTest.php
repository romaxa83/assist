<?php

namespace Tests\Feature\Api\Notes\Private\CrudController;

use App\Models\Notes\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Notes\NoteBuilder;
use Tests\Builders\Tags\TagBuilder;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use DatabaseTransactions;

    protected NoteBuilder $noteBuilder;
    protected TagBuilder $tagBuilder;

    public function setUp(): void
    {
        $this->noteBuilder = resolve(NoteBuilder::class);
        $this->tagBuilder = resolve(TagBuilder::class);

        parent::setUp();
    }

    public function test_success()
    {
        $this->loginAsAdmin();

        $tag_1 = $this->tagBuilder->weight(2)->create();
        $tag_2 = $this->tagBuilder->weight(3)->create();

        /** @var $model Note */
        $model = $this->noteBuilder->tags($tag_1, $tag_2)->create();

        $id = $model->id;

        $this->deleteJson(route('api.private.note.delete', ['id' => $model->id]))
            ->assertValidResponse(204)
        ;

        $this->assertNull(Note::find($id));

        $tag_1->refresh();
        $tag_2->refresh();

        $this->assertEquals(1, $tag_1->weight);
        $this->assertEquals(2, $tag_2->weight);
    }


    public function test_fail_not_found_model()
    {
        $this->loginAsAdmin();

        /** @var $model Note */
        $model = $this->noteBuilder->create();


        $res = $this->deleteJson(route('api.private.note.delete', ['id' => $model->id +1]))
        ;

        self::assertNotFound($res);
    }

    public function test_fail_not_auth()
    {
        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $res = $this->deleteJson(route('api.private.note.delete', ['id' => $model->id]))
        ;

        self::assertUnauthorized($res);
    }
}
