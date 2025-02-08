<?php

namespace Tests\Unit\Enums\Notes;

use App\Enums\Notes\NoteStatus;
use Tests\TestCase;

class GetStatusForChangeTest extends TestCase
{
    public function test_current_status_as_draft(): void
    {
        $this->assertEquals(
            NoteStatus::getStatusesForChange(NoteStatus::DRAFT), [
                [
                    'value' => NoteStatus::DRAFT->value,
                    'label' => NoteStatus::DRAFT->label(),
                ],
                [
                    'value' => NoteStatus::MODERATION->value,
                    'label' => NoteStatus::MODERATION->label(),
                ],
                [
                    'value' => NoteStatus::MODERATED->value,
                    'label' => NoteStatus::MODERATED->label(),
                ]
            ]
        );
    }

    public function test_current_status_as_moderation(): void
    {
        $this->assertEquals(
            NoteStatus::getStatusesForChange(NoteStatus::MODERATION), [
                [
                    'value' => NoteStatus::MODERATION->value,
                    'label' => NoteStatus::MODERATION->label(),
                ],
                [
                    'value' => NoteStatus::DRAFT->value,
                    'label' => NoteStatus::DRAFT->label(),
                ],
                [
                    'value' => NoteStatus::MODERATED->value,
                    'label' => NoteStatus::MODERATED->label(),
                ]
            ]
        );
    }

    public function test_current_status_as_moderated(): void
    {
        $this->assertEquals(
            NoteStatus::getStatusesForChange(NoteStatus::MODERATED), [
                [
                    'value' => NoteStatus::MODERATED->value,
                    'label' => NoteStatus::MODERATED->label(),
                ],
                [
                    'value' => NoteStatus::PUBLIC->value,
                    'label' => NoteStatus::PUBLIC->label(),
                ],
                [
                    'value' => NoteStatus::PRIVATE->value,
                    'label' => NoteStatus::PRIVATE->label(),
                ]
            ]
        );
    }

    public function test_current_status_as_public(): void
    {
        $this->assertEquals(
            NoteStatus::getStatusesForChange(NoteStatus::PUBLIC), [
                [
                    'value' => NoteStatus::PUBLIC->value,
                    'label' => NoteStatus::PUBLIC->label(),
                ],
                [
                    'value' => NoteStatus::PRIVATE->value,
                    'label' => NoteStatus::PRIVATE->label(),
                ]
            ]
        );
    }

    public function test_current_status_as_private(): void
    {
        $this->assertEquals(
            NoteStatus::getStatusesForChange(NoteStatus::PRIVATE), [
                [
                    'value' => NoteStatus::PRIVATE->value,
                    'label' => NoteStatus::PRIVATE->label(),
                ],
                [
                    'value' => NoteStatus::PUBLIC->value,
                    'label' => NoteStatus::PUBLIC->label(),
                ]
            ]
        );
    }
}

