<?php

namespace Tests\Feature\Api\Notes\Public\Controller;

use App\Enums\Notes\NoteStatus;
use App\Models\Notes\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Notes\NoteBuilder;
use Tests\Builders\Tags\TagBuilder;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use DatabaseTransactions;

    protected NoteBuilder $noteBuilder;
    protected TagBuilder $tagBuilder;

    protected array $data;

    public function setUp(): void
    {
        $this->noteBuilder = resolve(NoteBuilder::class);
        $this->tagBuilder = resolve(TagBuilder::class);
        parent::setUp();
    }

    public function test_show()
    {
        /** @var $model Note */
        $model = $this->noteBuilder
            ->status(NoteStatus::PUBLIC)
            ->tags($this->tagBuilder->create())
            ->links([[
                'title' => 'test',
                'link' => 'test',
            ]])
            ->anchors([[
                'tag' => 'test',
                'id' => 'test',
                'content' => 'test',
            ]])
            ->text_blocks([[
                'type' => 'test',
                'language' => 'test',
                'content' => 'test',
            ]])
            ->create();

        $this->getJson(route('api.note.show', ['slug' => $model->slug]))
            ->dump()
            ->assertJson([
                'id' => $model->id,
                'title' => $model->title,
                'slug' => $model->slug,
                'weight' => $model->weight,
                'created_at' => date_to_front($model->created_at),
            ])
            ->assertValidResponse(200)
        ;
    }

    public function test_fail_not_found_model()
    {
        $this->loginAsAdmin();

        /** @var $model Note */
        $model = $this->noteBuilder
            ->status(NoteStatus::PUBLIC)
            ->create();

        $res = $this->getJson(route('api.note.show', ['slug' => $model->slug . '1']))
        ;

        self::assertNotFound($res);
    }
}