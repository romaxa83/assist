<?php

namespace Tests\Feature\Api\Notes\Private\CrudController;

use App\Models\Notes\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Notes\NoteBuilder;
use Tests\TestCase;

class ShortListTest extends TestCase
{
    use DatabaseTransactions;

    protected NoteBuilder $noteBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->noteBuilder = resolve(NoteBuilder::class);
    }

    public function test_success()
    {
        $this->loginAsAdmin();

        /** @var $model_1 Note */
        $model_1 = $this->noteBuilder->weight(3)->create();
        $model_2 = $this->noteBuilder->weight(1)->create();
        $model_3 = $this->noteBuilder->weight(13)->create();

        $this->getJson(route('api.private.note.shortlist'))
            ->assertJson([
                ['id' => $model_3->id,],
                ['id' => $model_1->id,],
                ['id' => $model_2->id,],
            ])
            ->assertValidResponse(200)
        ;
    }

    public function test_success_empty_data()
    {
        $this->loginAsAdmin();

        $this->getJson(route('api.private.note.shortlist'))
            ->assertJson([])
            ->assertJsonCount(0)
        ;
    }

    public function test_fail_not_auth()
    {
        $this->noteBuilder->weight(3)->create();

        $res = $this->getJson(route('api.private.note.shortlist'))
        ;

        self::assertUnauthorized($res);
    }
}
