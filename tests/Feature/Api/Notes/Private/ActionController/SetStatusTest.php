<?php

namespace Tests\Feature\Api\Notes\Private\ActionController;

use App\Enums\Notes\NoteStatus;
use App\Models\Notes\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Notes\NoteBuilder;
use Tests\TestCase;

class SetStatusTest extends TestCase
{
    use DatabaseTransactions;

    protected NoteBuilder $noteBuilder;

    public function setUp(): void
    {
        parent::setUp();
        $this->noteBuilder = resolve(NoteBuilder::class);
    }

    public function test_success_set_status()
    {
        $this->loginAsAdmin();

        /** @var $model Note */
        $model = $this->noteBuilder
            ->status(NoteStatus::DRAFT)
            ->create();

        $data = [
            'status' => NoteStatus::PUBLIC(),
        ];

        $this->postJson(route('api.private.note.set-status', [
            'id' => $model->id,
        ]),$data)
            ->assertJson([
                'id' => $model->id,
                'status' => $data['status'],
            ])
//            ->assertValidResponse(200)
        ;
    }

    public function test_not_auth()
    {
        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $data = [
            'status' => NoteStatus::PUBLIC(),
        ];

        $res = $this->postJson(route('api.private.note.set-status', ['id' => $model->id]), $data)
        ;

        self::assertUnauthorized($res);
    }
}
