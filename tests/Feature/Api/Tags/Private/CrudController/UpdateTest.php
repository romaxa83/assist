<?php

namespace Tests\Feature\Api\Tags\Private\CrudController;

use App\Models\Tags\Tag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
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

    public function test_update()
    {
        $this->loginAsAdmin();

        /** @var $model Tag */
        $model = $this->tagBuilder->create();

        $data = $this->data;

        $this->assertNotEquals($model->name, $data['name']);
        $this->assertNotEquals($model->slug, slug($data['name']));
        $this->assertNotEquals($model->color, $data['color']);

        $this->putJson(route('api.private.tag.update', ['id' => $model->id]), $data)
            ->assertJson([
                'id' => $model->id,
                'name' => $data['name'],
                'slug' => slug($data['name']),
                'color' => $data['color'],
                'public_attached' => $model->public_attached,
                'private_attached' => $model->private_attached,
            ])
//            ->assertValidResponse(200)
        ;
    }

    public function test_update_not_unique_name()
    {
        $this->loginAsAdmin();

        /** @var $model Tag */
        $model = $this->tagBuilder->create();

        $data = $this->data;
        $data['name'] = $model->name;

        $this->assertEquals($model->name, $data['name']);
        $this->assertNotEquals($model->color, $data['color']);

        $this->putJson(route('api.private.tag.update', ['id' => $model->id]), $data)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'id' => $model->id,
                'name' => $data['name'],
                'slug' => slug($data['name']),
                'color' => $data['color'],
            ])
        ;
    }

    public function test_fail_unique_name()
    {
        $this->loginAsAdmin();

        /** @var $model Tag */
        $model = $this->tagBuilder->create();

        $anotherModel = $this->tagBuilder->create();

        $data = $this->data;
        $data['name'] = $anotherModel->name;

        $res = $this->putJson(route('api.private.tag.update', ['id' => $model->id]), $data)
        ;

        self::assertValidationError(
            $res,
            __('validation.attributes.name'),
            __('validation.unique', ['attribute' => __('validation.attributes.name')])
        );
    }

    public function test_fail_not_found_model()
    {
        $this->loginAsAdmin();

        /** @var $model Tag */
        $model = $this->tagBuilder->create();

        $data = $this->data;

        $res = $this->putJson(route('api.private.tag.update', ['id' => $model->id + 1]), $data)
        ;

        self::assertNotFound($res);
    }

    public function test_not_auth()
    {
        /** @var $model Tag */
        $model = $this->tagBuilder->create();

        $data = $this->data;

        $res = $this->putJson(route('api.private.tag.update', ['id' => $model->id]), $data)
        ;

        self::assertUnauthorized($res);
    }
}
