<?php

namespace Tests\Feature\Api\Tags\CrudController;

use App\Models\Tags\Tag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Tags\TagBuilder;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;

    protected TagBuilder $tagBuilder;

    protected array $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->tagBuilder = resolve(TagBuilder::class);

        $this->data = [
            'name' => 'test',
            'color' => '#d98b84',
        ];
    }

    public function test_success()
    {
        $this->loginAsAdmin();

        $data = $this->data;

        $this->postJson(route('api.tag.create'), $data)
            ->assertValidResponse(201)
        ;
    }

    public function test_fail_unique_name()
    {
        $this->loginAsAdmin();

        /** @var $model Tag */
        $model = $this->tagBuilder->create();

        $data = $this->data;
        $data['name'] = $model->name;

        $res = $this->postJson(route('api.tag.create'), $data)
        ;

        self::assertValidationError(
            $res,
            __('validation.attributes.name'),
            __('validation.unique', ['attribute' => __('validation.attributes.name')])
        );
    }

    public function test_fail_not_auth()
    {
        $data = $this->data;

        $res = $this->postJson(route('api.tag.create'), $data)
        ;

        self::assertUnauthorized($res);
    }
}
