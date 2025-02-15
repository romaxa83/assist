<?php

namespace Tests\Feature\Api\Notes\Private\CrudController;

use App\Enums\Notes\NoteStatus;
use App\Models\Notes\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Notes\NoteBuilder;
use Tests\Builders\Tags\TagBuilder;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;

    protected NoteBuilder $noteBuilder;
    protected TagBuilder $tagBuilder;

    protected array $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->noteBuilder = resolve(NoteBuilder::class);
        $this->tagBuilder = resolve(TagBuilder::class);

        $this->data = [
            'title' => 'test title',
            'text' => 'some text',
        ];
    }

    public function test_success()
    {
        $this->loginAsAdmin();

        $tag_1 = $this->tagBuilder->weight(1)->create();
        $tag_2 = $this->tagBuilder->weight(10)->create();

        $data = $this->data;
        $data['tags'] = [
            $tag_1->id,
            $tag_2->id,
        ];

        $this->postJson(route('api.private.note.create'), $data)
            ->assertJson([
                'status' => NoteStatus::DRAFT(),
                'title' => $data['title'],
                'text' => $data['text'],
                'weight' => 0,
                'tags' => [
                    ['id' => $tag_2->id],
                    ['id' => $tag_1->id],
                ]
            ])
            ->assertJsonCount(2, 'tags')
//            ->assertValidResponse(201)
        ;

        $tag_1->refresh();
        $tag_2->refresh();

        $this->assertEquals(2, $tag_1->weight);
        $this->assertEquals(11, $tag_2->weight);
    }

    public function test_success_create_without_tags()
    {
        $this->loginAsAdmin();

        $data = $this->data;

        $this->postJson(route('api.private.note.create'), $data)
            ->assertJsonCount(0, 'tags')
        ;
    }

    public function test_fail_unique_title()
    {
        $this->loginAsAdmin();

        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $data = $this->data;
        $data['title'] = $model->title;

        $res = $this->postJson(route('api.private.note.create'), $data)
        ;

        self::assertValidationError(
            $res,
            __('validation.attributes.title'),
            __('validation.unique', ['attribute' => __('validation.attributes.title')])
        );
    }

    public function test_not_auth()
    {
        $data = $this->data;

        $res = $this->postJson(route('api.private.note.create'), $data)
        ;

        self::assertUnauthorized($res);
    }
}
