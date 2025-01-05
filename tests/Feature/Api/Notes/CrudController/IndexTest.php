<?php

namespace Tests\Feature\Api\Notes\CrudController;

use App\Models\Notes\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Notes\NoteBuilder;
use Tests\Builders\Tags\TagBuilder;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use DatabaseTransactions;

    protected NoteBuilder $noteBuilder;
    protected TagBuilder $tagBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->tagBuilder = resolve(TagBuilder::class);
        $this->noteBuilder = resolve(NoteBuilder::class);
    }

    public function test_success()
    {
        $this->loginAsAdmin();

        /** @var $model_1 Note */
        $model_1 = $this->noteBuilder->weight(3)->create();
        $model_2 = $this->noteBuilder->weight(1)->create();
        $model_3 = $this->noteBuilder->weight(13)->create();

        $this->getJson(route('api.note.index'))
            ->assertJson([
                'data' => [
                    ['id' => $model_3->id,],
                    ['id' => $model_1->id,],
                    ['id' => $model_2->id,],
                ],
                'meta' => [
                    'current_page' => 1,
                    'to' => 3,
                    'total' => 3
                ]
            ])
            ->assertValidResponse(200)
        ;
    }

    public function test_success_by_page()
    {
        $this->loginAsAdmin();

        $this->noteBuilder->weight(3)->create();
        $this->noteBuilder->weight(1)->create();
        $this->noteBuilder->weight(13)->create();

        $this->getJson(route('api.note.index', [
            'page' => 2,
        ]))
            ->assertJson([
                'meta' => [
                    'current_page' => 2,
                    'per_page' => Note::DEFAULT_PER_PAGE,
                    'total' => 3,
                    'to' => null
                ]
            ])
        ;
    }

    public function test_success_by_per_page()
    {
        $this->loginAsAdmin();

        $this->noteBuilder->weight(3)->create();
        $this->noteBuilder->weight(1)->create();
        $this->noteBuilder->weight(13)->create();

        $this->getJson(route('api.note.index', [
            'per_page' => 2,
        ]))
            ->assertJson([
                'meta' => [
                    'current_page' => 1,
                    'per_page' => 2,
                    'total' => 3,
                    'to' => 2
                ]
            ])
        ;
    }

    public function test_success_filter_by_tags()
    {
        $this->loginAsAdmin();

        $tag_1 = $this->tagBuilder->create();
        $tag_2 = $this->tagBuilder->create();
        $tag_3 = $this->tagBuilder->create();

        /** @var $model_1 Note */
        $model_1 = $this->noteBuilder
            ->tags($tag_1, $tag_2)
            ->weight(3)
            ->create();
        $model_2 = $this->noteBuilder
            ->tags($tag_2)
            ->weight(1)
            ->create();
        $model_3 = $this->noteBuilder
            ->tags($tag_3, $tag_2)
            ->weight(13)
            ->create();

        $this->getJson(route('api.note.index', [
            'tags' => [$tag_1->id, $tag_3->id]
        ]))
            ->assertJson([
                'data' => [
                    ['id' => $model_3->id,],
                    ['id' => $model_1->id,],
                ],
                'meta' => [
                    'current_page' => 1,
                    'to' => 2,
                    'total' => 2
                ]
            ])
        ;
    }

    public function test_success_search()
    {
        $this->loginAsAdmin();

        /** @var $model_1 Note */
        $model_1 = $this->noteBuilder
            ->title('Заготовки для нормального поиска')
            ->text('Чтоб реализовать крутой поиск нужно создать правильные заготовки')
            ->weight(1)
            ->create();
        $model_2 = $this->noteBuilder
            ->title('Связи в ларавел')
            ->text('В ларавел существуют такие связи')
            ->weight(1)
            ->create();
        $model_3 = $this->noteBuilder
            ->title('Фасетный поиска')
            ->text('Чтоб реализовать фасетный поиск нужно ....')
            ->weight(13)
            ->create();

        $this->getJson(route('api.note.index', [
            'search' => 'Загатовки поиск'
        ]))
            ->assertJson([
                'data' => [
                    ['id' => $model_1->id,],
                    ['id' => $model_3->id,],
                ],
                'meta' => [
                    'current_page' => 1,
                    'to' => 2,
                    'total' => 2
                ]
            ])
        ;
    }

    public function test_success_empty_data()
    {
        $this->loginAsAdmin();

        $this->getJson(route('api.note.index'))
            ->assertJson([])
            ->assertJsonCount(0, 'data')
        ;
    }

    public function test_fail_not_auth()
    {
        $this->noteBuilder->weight(3)->create();

        $res = $this->getJson(route('api.note.index'))
        ;

        self::assertUnauthorized($res);
    }
}
