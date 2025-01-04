<?php

namespace Tests\Feature\Api\Notes\CrudController;

use App\Models\Notes\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Notes\NoteBuilder;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use DatabaseTransactions;

    protected NoteBuilder $noteBuilder;

    public function setUp(): void
    {
        $this->noteBuilder = resolve(NoteBuilder::class);

        parent::setUp();
    }

    public function test_success()
    {
        $this->loginAsAdmin();

        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $id = $model->id;

        $this->deleteJson(route('api.note.delete', ['id' => $model->id]))
            ->assertValidResponse(204)
        ;

        $this->assertNull(Note::find($id));
    }


    public function test_fail_not_found_model()
    {
        $this->loginAsAdmin();

        /** @var $model Note */
        $model = $this->noteBuilder->create();


        $res = $this->deleteJson(route('api.note.delete', ['id' => $model->id +1]))
        ;

        self::assertNotFound($res);
    }

    public function test_fail_not_auth()
    {
        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $res = $this->deleteJson(route('api.note.delete', ['id' => $model->id]))
        ;

        self::assertUnauthorized($res);
    }
}
