<?php

namespace Tests\Feature\Api\Notes\Private\CrudController;

use App\Models\Notes\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Notes\NoteBuilder;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use DatabaseTransactions;

    protected NoteBuilder $noteBuilder;

    protected array $data;

    public function setUp(): void
    {
        $this->noteBuilder = resolve(NoteBuilder::class);
        parent::setUp();
    }

    public function test_show()
    {
        $user = $this->loginAsAdmin();

        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $this->getJson(route('api.private.note.show', ['id' => $model->id]))
            ->assertJson([
                'id' => $model->id,
                'title' => $model->title,
                'slug' => $model->slug,
                'text' => $model->text,
                'created_at' => date_to_front($model->created_at),
                'meta' => $model->getMeta($user)
            ])
//            ->assertValidResponse(200)
        ;
    }

    public function test_fail_not_found_model()
    {
        $this->loginAsAdmin();

        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $res = $this->getJson(route('api.private.note.show', ['id' => $model->id + 1]))
        ;

        self::assertNotFound($res);
    }

    public function test_not_auth()
    {
        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $res = $this->getJson(route('api.private.note.show', ['id' => $model->id]))
        ;

        self::assertUnauthorized($res);
    }
}