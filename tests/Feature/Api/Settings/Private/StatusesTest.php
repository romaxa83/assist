<?php

namespace Tests\Feature\Api\Settings\Private;

use App\Enums\Notes\NoteStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Notes\NoteBuilder;
use Tests\TestCase;

class StatusesTest extends TestCase
{
    use DatabaseTransactions;

    protected NoteBuilder $noteBuilder;

    public function setUp(): void
    {
        parent::setUp();
        $this->noteBuilder = resolve(NoteBuilder::class);
    }

    public function test_get_statuses()
    {
        $this->loginAsAdmin();

        $this->getJson(route('api.private.settings.notes'))
            ->assertJson([
                'statuses' => [
                    [
                        'value' => NoteStatus::DRAFT(),
                        'label' => NoteStatus::DRAFT->label(),
                    ],
                    [
                        'value' => NoteStatus::MODERATION(),
                        'label' => NoteStatus::MODERATION->label(),
                    ],
                    [
                        'value' => NoteStatus::MODERATED(),
                        'label' => NoteStatus::MODERATED->label(),
                    ],
                    [
                        'value' => NoteStatus::PUBLIC(),
                        'label' => NoteStatus::PUBLIC->label(),
                    ],
                    [
                        'value' => NoteStatus::PRIVATE(),
                        'label' => NoteStatus::PRIVATE->label(),
                    ]
                ],
            ])
            ->assertJsonCount(5, 'statuses')
        ;
    }

    public function test_not_auth()
    {
        $this->noteBuilder->create();

        $res = $this->getJson(route('api.private.settings.notes'))
        ;

        self::assertUnauthorized($res);
    }
}
