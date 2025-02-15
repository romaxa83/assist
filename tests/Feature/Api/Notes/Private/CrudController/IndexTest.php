<?php

namespace Tests\Feature\Api\Notes\Private\CrudController;

use App\Enums\DateFormat;
use App\Enums\Notes\NoteStatus;
use App\Models\Notes\Note;
use Carbon\CarbonImmutable;
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

        $now = CarbonImmutable::now();

        /** @var $model_1 Note */
        $model_1 = $this->noteBuilder
            ->created_at($now->subDays(3))
            ->create();
        $model_2 = $this->noteBuilder
            ->created_at($now)
            ->create();
        $model_3 = $this->noteBuilder
            ->created_at($now->subDays(2))
            ->create();

        $this->getJson(route('api.private.note.index'))
            ->assertJson([
                'data' => [
                    ['id' => $model_2->id,],
                    ['id' => $model_3->id,],
                    ['id' => $model_1->id,],
                ],
                'meta' => [
                    'current_page' => 1,
                    'to' => 3,
                    'total' => 3
                ]
            ])
//            ->assertValidResponse(200)
        ;
    }

    public function test_success_by_page()
    {
        $this->loginAsAdmin();

        $this->noteBuilder->weight(3)->create();
        $this->noteBuilder->weight(1)->create();
        $this->noteBuilder->weight(13)->create();

        $this->getJson(route('api.private.note.index', [
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

        $this->getJson(route('api.private.note.index', [
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

        $now = CarbonImmutable::now();

        $tag_1 = $this->tagBuilder->create();
        $tag_2 = $this->tagBuilder->create();
        $tag_3 = $this->tagBuilder->create();

        /** @var $model_1 Note */
        $model_1 = $this->noteBuilder
            ->tags($tag_1, $tag_2)
            ->created_at($now->subDays(2))
            ->create();
        $model_2 = $this->noteBuilder
            ->tags($tag_2)
            ->created_at($now)
            ->create();
        $model_3 = $this->noteBuilder
            ->tags($tag_3, $tag_2)
            ->created_at($now->subDays(3))
            ->create();

        $this->getJson(route('api.private.note.index', [
            'tags' => [$tag_1->id, $tag_3->id]
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

    public function test_success_filter_by_status()
    {
        $this->loginAsAdmin();

        $now = CarbonImmutable::now();

        /** @var $model_3 Note */
        $this->noteBuilder
            ->status(NoteStatus::DRAFT)
            ->created_at($now->subDays(2))
            ->create();
        $this->noteBuilder
            ->status(NoteStatus::PUBLIC)
            ->created_at($now)
            ->create();
        $model_3 = $this->noteBuilder
            ->status(NoteStatus::PRIVATE)
            ->created_at($now->subDays(3))
            ->create();

        $this->getJson(route('api.private.note.index', [
            'status' => NoteStatus::PRIVATE()
        ]))
            ->assertJson([
                'data' => [
                    ['id' => $model_3->id,],
                ],
                'meta' => [
                    'total' => 1
                ]
            ])
        ;
    }

    public function test_success_filter_by_range_date()
    {
        $this->loginAsAdmin();

        $now = CarbonImmutable::now();

        /** @var $model_3 Note */
        $model_1 = $this->noteBuilder
            ->created_at($now->subDays(1))
            ->create();
        $model_2 =  $this->noteBuilder
            ->created_at($now)
            ->create();
        $model_3 = $this->noteBuilder
            ->created_at($now->subDays(4))
            ->create();

        $this->getJson(route('api.private.note.index', [
            'start_date' => $now->subDays(2)->format(DateFormat::FRONT_FILTER()),
            'end_date' => $now->format(DateFormat::FRONT_FILTER()),
        ]))
            ->assertJson([
                'data' => [
                    ['id' => $model_2->id,],
                    ['id' => $model_1->id,],
                ],
                'meta' => [
                    'total' => 2
                ]
            ])
        ;
    }

    public function test_success_sort_by_weight_desc()
    {
        $this->loginAsAdmin();

        $now = CarbonImmutable::now();

        /** @var $model_3 Note */
        $model_1 = $this->noteBuilder
            ->weight(1)
            ->created_at($now)
            ->create();
        $model_2 =  $this->noteBuilder
            ->weight(18)
            ->created_at($now)
            ->create();
        $model_3 = $this->noteBuilder
            ->weight(4)
            ->created_at($now)
            ->create();

        $this->getJson(route('api.private.note.index', [
            'sort' => ['weight-desc'],
        ]))
            ->assertJson([
                'data' => [
                    ['id' => $model_2->id,],
                    ['id' => $model_3->id,],
                    ['id' => $model_1->id,],
                ],
                'meta' => [
                    'total' => 3
                ]
            ])
        ;
    }

    public function test_success_sort_by_weight_asc()
    {
        $this->loginAsAdmin();

        $now = CarbonImmutable::now();

        /** @var $model_3 Note */
        $model_1 = $this->noteBuilder
            ->weight(1)
            ->created_at($now)
            ->create();
        $model_2 =  $this->noteBuilder
            ->weight(18)
            ->created_at($now)
            ->create();
        $model_3 = $this->noteBuilder
            ->weight(4)
            ->created_at($now)
            ->create();

        $this->getJson(route('api.private.note.index', [
            'sort' => ['weight-asc'],
        ]))
            ->assertJson([
                'data' => [
                    ['id' => $model_1->id,],
                    ['id' => $model_3->id,],
                    ['id' => $model_2->id,],
                ],
                'meta' => [
                    'total' => 3
                ]
            ])
        ;
    }

    public function test_success_sort_by_created_at_desc_and_weight_desc()
    {
        $this->loginAsAdmin();

        $now = CarbonImmutable::now();

        /** @var $model_3 Note */
        $model_1 = $this->noteBuilder
            ->weight(1)
            ->created_at($now)
            ->create();
        $model_2 =  $this->noteBuilder
            ->weight(18)
            ->created_at($now)
            ->create();
        $model_3 = $this->noteBuilder
            ->weight(4)
            ->created_at($now->subDays(4))
            ->create();
        $model_4 = $this->noteBuilder
            ->weight(5)
            ->created_at($now->subDays(3))
            ->create();
        $model_5 = $this->noteBuilder
            ->weight(9)
            ->created_at($now->subDays(5))
            ->create();
        $model_6 = $this->noteBuilder
            ->weight(0)
            ->created_at($now->subDays(1))
            ->create();

        $this->getJson(route('api.private.note.index', [
            'sort' => ['weight-desc', 'created_at-desc'],
        ]))
            ->assertJson([
                'data' => [
                    ['id' => $model_2->id,],
                    ['id' => $model_5->id,],
                    ['id' => $model_4->id,],
                    ['id' => $model_3->id,],
                    ['id' => $model_1->id,],
                    ['id' => $model_6->id,],
                ],
                'meta' => [
                    'total' => 6
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

        $this->getJson(route('api.private.note.index', [
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

        $this->getJson(route('api.private.note.index'))
            ->assertJson([])
            ->assertJsonCount(0, 'data')
        ;
    }

    public function test_not_auth()
    {
        $this->noteBuilder->create();

        $res =  $this->getJson(route('api.private.note.index'))
        ;

        self::assertUnauthorized($res);
    }
}
