<?php

namespace Tests\Feature\Api\Tags\CrudController;

use App\Models\Tags\Tag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Tags\TagBuilder;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    protected TagBuilder $tagBuilder;

    protected array $data;

    public function setUp(): void
    {
        $this->tagBuilder = resolve(TagBuilder::class);

        $this->data = [
            'name' => 'test',
            'color' => '#d98b84',
        ];
        parent::setUp();
    }

    public function test_success()
    {
        $this->loginAsAdmin();

        /** @var $model Tag */
        $model = $this->tagBuilder->create();

        $data = $this->data;

        $this->assertNotEquals($model->name, $data['name']);
        $this->assertNotEquals($model->slug, slug($data['name']));
        $this->assertNotEquals($model->color, $data['color']);

        $this->putJson(route('api.tag.update', ['id' => $model->id]), $data)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $model->id,
                    'name' => $data['name'],
                    'slug' => slug($data['name']),
                    'color' => $data['color'],
                ]
            ])
        ;
    }

    public function test_fail_not_auth()
    {
        /** @var $model Tag */
        $model = $this->tagBuilder->create();

        $data = $this->data;

        $res = $this->putJson(route('api.tag.update', ['id' => $model->id]), $data)
        ;

        self::assertUnauthorizedMsg($res);
    }
}
