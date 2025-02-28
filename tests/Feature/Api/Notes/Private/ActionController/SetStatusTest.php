<?php

namespace Tests\Feature\Api\Notes\Private\ActionController;

use App\Enums\Notes\NoteStatus;
use App\Models\Notes\Note;
use App\Models\Tags\Tag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Notes\NoteBuilder;
use Tests\Builders\Tags\TagBuilder;
use Tests\TestCase;

class SetStatusTest extends TestCase
{
    use DatabaseTransactions;

    protected TagBuilder $tagBuilder;
    protected NoteBuilder $noteBuilder;

    public function setUp(): void
    {
        parent::setUp();
        $this->tagBuilder = resolve(TagBuilder::class);
        $this->noteBuilder = resolve(NoteBuilder::class);
    }

    public function test_from_draft_to_moderation()
    {
        $this->loginAsAdmin();

        /** @var $tag_1 Tag */
        $tag_1 = $this->tagBuilder
            ->public_attached(3)
            ->private_attached(2)
            ->create();
        $tag_2 = $this->tagBuilder
            ->public_attached(5)
            ->private_attached(6)
            ->create();

        /** @var $model Note */
        $model = $this->noteBuilder
            ->status(NoteStatus::DRAFT)
            ->tags($tag_1, $tag_2)
            ->create();

        $data = [
            'status' => NoteStatus::MODERATION(),
        ];

        $this->postJson(route('api.private.note.set-status', [
            'id' => $model->id,
        ]),$data)
            ->assertJson([
                'id' => $model->id,
                'status' => $data['status'],
                'meta' => [
                    'statuses' => [
                        ['value' => NoteStatus::DRAFT->value],
                        ['value' => NoteStatus::MODERATION->value],
                        ['value' => NoteStatus::PUBLIC->value],
                        ['value' => NoteStatus::PRIVATE->value],
                    ]
                ]
            ])
            ->assertJsonCount(4, 'meta.statuses')
//            ->assertValidResponse(200)
        ;

        $tag_1->refresh();
        $tag_2->refresh();

        $this->assertEquals(3, $tag_1->public_attached);
        $this->assertEquals(2, $tag_1->private_attached);

        $this->assertEquals(5, $tag_2->public_attached);
        $this->assertEquals(6, $tag_2->private_attached);
    }

//    public function test_from_draft_to_public()
//    {
//        $this->loginAsAdmin();
//
//        /** @var $tag_1 Tag */
//        $tag_1 = $this->tagBuilder
//            ->public_attached(3)
//            ->private_attached(2)
//            ->create();
//        $tag_2 = $this->tagBuilder
//            ->public_attached(5)
//            ->private_attached(6)
//            ->create();
//
//        /** @var $model Note */
//        $model = $this->noteBuilder
//            ->status(NoteStatus::DRAFT)
//            ->tags($tag_1, $tag_2)
//            ->create();
//
//        $data = [
//            'status' => NoteStatus::PUBLIC(),
//        ];
//
//        $this->postJson(route('api.private.note.set-status', [
//            'id' => $model->id,
//        ]),$data)
//            ->assertJson([
//                'id' => $model->id,
//                'status' => $data['status'],
//                'meta' => [
//                    'statuses' => [
//                        ['value' => NoteStatus::DRAFT->value],
//                        ['value' => NoteStatus::MODERATION->value],
//                        ['value' => NoteStatus::PUBLIC->value],
//                        ['value' => NoteStatus::PRIVATE->value],
//                    ]
//                ]
//            ])
//            ->assertJsonCount(4, 'meta.statuses')
////            ->assertValidResponse(200)
//        ;
//
//        $tag_1->refresh();
//        $tag_2->refresh();
//
//        $this->assertEquals(3, $tag_1->public_attached);
//        $this->assertEquals(2, $tag_1->private_attached);
//
//        $this->assertEquals(5, $tag_2->public_attached);
//        $this->assertEquals(6, $tag_2->private_attached);
//    }

    public function test_from_moderation_to_draft()
    {
        $this->loginAsAdmin();

        /** @var $tag_1 Tag */
        $tag_1 = $this->tagBuilder
            ->public_attached(3)
            ->private_attached(2)
            ->create();

        /** @var $model Note */
        $model = $this->noteBuilder
            ->status(NoteStatus::MODERATION)
            ->tags($tag_1)
            ->create();

        $data = [
            'status' => NoteStatus::DRAFT(),
        ];

        $this->postJson(route('api.private.note.set-status', [
            'id' => $model->id,
        ]),$data)
            ->assertJson([
                'id' => $model->id,
                'status' => $data['status'],
                'meta' => [
                    'statuses' => [
                        ['value' => NoteStatus::DRAFT->value],
                        ['value' => NoteStatus::MODERATION->value]
                    ]
                ]
            ])
            ->assertJsonCount(2, 'meta.statuses')
        ;

        $tag_1->refresh();

        $this->assertEquals(3, $tag_1->public_attached);
        $this->assertEquals(2, $tag_1->private_attached);
    }

    public function test_from_moderation_to_public()
    {
        $this->loginAsAdmin();

        /** @var $tag_1 Tag */
        $tag_1 = $this->tagBuilder
            ->public_attached(3)
            ->private_attached(2)
            ->create();
        $tag_2 = $this->tagBuilder
            ->public_attached(5)
            ->private_attached(6)
            ->create();

        /** @var $model Note */
        $model = $this->noteBuilder
            ->status(NoteStatus::MODERATION)
            ->tags($tag_1, $tag_2)
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
                'meta' => [
                    'statuses' => [
                        ['value' => NoteStatus::MODERATION->value],
                        ['value' => NoteStatus::PUBLIC->value],
                        ['value' => NoteStatus::PRIVATE->value],
                    ]
                ]
            ])
            ->assertJsonCount(3, 'meta.statuses')
        ;

        $tag_1->refresh();
        $tag_2->refresh();

        $this->assertEquals(4, $tag_1->public_attached);
        $this->assertEquals(2, $tag_1->private_attached);

        $this->assertEquals(6, $tag_2->public_attached);
        $this->assertEquals(6, $tag_2->private_attached);
    }

    public function test_from_moderation_to_private()
    {
        $this->loginAsAdmin();

        /** @var $tag_1 Tag */
        $tag_1 = $this->tagBuilder
            ->public_attached(3)
            ->private_attached(2)
            ->create();
        $tag_2 = $this->tagBuilder
            ->public_attached(5)
            ->private_attached(6)
            ->create();

        /** @var $model Note */
        $model = $this->noteBuilder
            ->status(NoteStatus::MODERATION)
            ->tags($tag_1, $tag_2)
            ->create();

        $data = [
            'status' => NoteStatus::PRIVATE(),
        ];

        $this->postJson(route('api.private.note.set-status', [
            'id' => $model->id,
        ]),$data)
            ->assertJson([
                'id' => $model->id,
                'status' => $data['status'],
                'meta' => [
                    'statuses' => [
                        ['value' => NoteStatus::MODERATION->value],
                        ['value' => NoteStatus::PUBLIC->value],
                        ['value' => NoteStatus::PRIVATE->value],
                    ]
                ]
            ])
            ->assertJsonCount(3, 'meta.statuses')
        ;

        $tag_1->refresh();
        $tag_2->refresh();

        $this->assertEquals(4, $tag_1->public_attached);
        $this->assertEquals(3, $tag_1->private_attached);

        $this->assertEquals(6, $tag_2->public_attached);
        $this->assertEquals(7, $tag_2->private_attached);
    }

    public function test_from_public_to_moderation()
    {
        $this->loginAsAdmin();

        /** @var $tag_1 Tag */
        $tag_1 = $this->tagBuilder
            ->public_attached(3)
            ->private_attached(2)
            ->create();
        $tag_2 = $this->tagBuilder
            ->public_attached(5)
            ->private_attached(6)
            ->create();

        /** @var $model Note */
        $model = $this->noteBuilder
            ->status(NoteStatus::PUBLIC)
            ->tags($tag_1, $tag_2)
            ->create();

        $data = [
            'status' => NoteStatus::MODERATION(),
        ];

        $this->postJson(route('api.private.note.set-status', [
            'id' => $model->id,
        ]),$data)
            ->assertJson([
                'id' => $model->id,
                'status' => $data['status'],
                'meta' => [
                    'statuses' => [
                        ['value' => NoteStatus::DRAFT->value],
                        ['value' => NoteStatus::MODERATION->value],
                        ['value' => NoteStatus::PUBLIC->value],
                        ['value' => NoteStatus::PRIVATE->value],
                    ]
                ]
            ])
            ->assertJsonCount(4, 'meta.statuses')
        ;

        $tag_1->refresh();
        $tag_2->refresh();

        $this->assertEquals(2, $tag_1->public_attached);
        $this->assertEquals(2, $tag_1->private_attached);

        $this->assertEquals(4, $tag_2->public_attached);
        $this->assertEquals(6, $tag_2->private_attached);
    }

    public function test_from_public_to_private()
    {
        $this->loginAsAdmin();

        /** @var $tag_1 Tag */
        $tag_1 = $this->tagBuilder
            ->public_attached(3)
            ->private_attached(2)
            ->create();
        $tag_2 = $this->tagBuilder
            ->public_attached(5)
            ->private_attached(6)
            ->create();

        /** @var $model Note */
        $model = $this->noteBuilder
            ->status(NoteStatus::PUBLIC)
            ->tags($tag_1, $tag_2)
            ->create();

        $data = [
            'status' => NoteStatus::PRIVATE(),
        ];

        $this->postJson(route('api.private.note.set-status', [
            'id' => $model->id,
        ]),$data)
            ->assertJson([
                'id' => $model->id,
                'status' => $data['status'],
                'meta' => [
                    'statuses' => [
                        ['value' => NoteStatus::MODERATION->value],
                        ['value' => NoteStatus::PUBLIC->value],
                        ['value' => NoteStatus::PRIVATE->value],
                    ]
                ]
            ])
            ->assertJsonCount(3, 'meta.statuses')
        ;

        $tag_1->refresh();
        $tag_2->refresh();

        $this->assertEquals(3, $tag_1->public_attached);
        $this->assertEquals(3, $tag_1->private_attached);

        $this->assertEquals(5, $tag_2->public_attached);
        $this->assertEquals(7, $tag_2->private_attached);
    }

    public function test_from_private_to_public()
    {
        $this->loginAsAdmin();

        /** @var $tag_1 Tag */
        $tag_1 = $this->tagBuilder
            ->public_attached(3)
            ->private_attached(2)
            ->create();
        $tag_2 = $this->tagBuilder
            ->public_attached(5)
            ->private_attached(6)
            ->create();

        /** @var $model Note */
        $model = $this->noteBuilder
            ->status(NoteStatus::PRIVATE)
            ->tags($tag_1, $tag_2)
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
                'meta' => [
                    'statuses' => [
                        ['value' => NoteStatus::MODERATION->value],
                        ['value' => NoteStatus::PUBLIC->value],
                        ['value' => NoteStatus::PRIVATE->value],
                    ]
                ]
            ])
            ->assertJsonCount(3, 'meta.statuses')
        ;

        $tag_1->refresh();
        $tag_2->refresh();

        $this->assertEquals(3, $tag_1->public_attached);
        $this->assertEquals(1, $tag_1->private_attached);

        $this->assertEquals(5, $tag_2->public_attached);
        $this->assertEquals(5, $tag_2->private_attached);
    }

    public function test_from_private_to_moderation()
    {
        $this->loginAsAdmin();

        /** @var $tag_1 Tag */
        $tag_1 = $this->tagBuilder
            ->public_attached(3)
            ->private_attached(2)
            ->create();
        $tag_2 = $this->tagBuilder
            ->public_attached(5)
            ->private_attached(6)
            ->create();

        /** @var $model Note */
        $model = $this->noteBuilder
            ->status(NoteStatus::PRIVATE)
            ->tags($tag_1, $tag_2)
            ->create();

        $data = [
            'status' => NoteStatus::MODERATION(),
        ];

        $this->postJson(route('api.private.note.set-status', [
            'id' => $model->id,
        ]),$data)
            ->assertJson([
                'id' => $model->id,
                'status' => $data['status'],
                'meta' => [
                    'statuses' => [
                        ['value' => NoteStatus::DRAFT->value],
                        ['value' => NoteStatus::MODERATION->value],
                        ['value' => NoteStatus::PUBLIC->value],
                        ['value' => NoteStatus::PRIVATE->value],
                    ]
                ]
            ])
            ->assertJsonCount(4, 'meta.statuses')
        ;

        $tag_1->refresh();
        $tag_2->refresh();

        $this->assertEquals(2, $tag_1->public_attached);
        $this->assertEquals(1, $tag_1->private_attached);

        $this->assertEquals(4, $tag_2->public_attached);
        $this->assertEquals(5, $tag_2->private_attached);
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
