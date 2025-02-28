<?php

namespace Tests\Feature\Api\Notes\Private\CrudController;

use App\Models\Notes\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\Builders\Notes\NoteBuilder;
use Tests\Builders\Tags\TagBuilder;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    protected NoteBuilder $noteBuilder;
    protected TagBuilder $tagBuilder;

    protected array $data;

    public function setUp(): void
    {
        $this->noteBuilder = resolve(NoteBuilder::class);
        $this->tagBuilder = resolve(TagBuilder::class);

        $this->data = [
            'title' => 'test title',
            'text' => 'some text',
        ];
        parent::setUp();
    }

    public function test_update()
    {
        $this->loginAsAdmin();

        $tag_1 = $this->tagBuilder->private_attached(1)->create();
        $tag_2 = $this->tagBuilder->private_attached(3)->create();

        /** @var $model Note */
        $model = $this->noteBuilder->tags($tag_1)->create();

        $data = $this->data;
        $data['tags'] = [
            $tag_1->id,
            $tag_2->id,
        ];

        $this->assertNotEquals($model->title, $data['title']);
        $this->assertNotEquals($model->slug, slug($data['title']));
        $this->assertNotEquals($model->text, $data['text']);

        $this->assertCount(1, $model->tags);

        $this->putJson(route('api.private.note.update', ['id' => $model->id]), $data)
            ->assertJson([
                'id' => $model->id,
                'title' => $data['title'],
                'slug' => slug($data['title']),
                'text' => $data['text'],
                'tags' => [
                    ['id' => $tag_1->id],
                    ['id' => $tag_2->id],
                ]
            ])
            ->assertJsonCount(2, 'tags')
//            ->assertValidResponse(200)
        ;

        $tag_1->refresh();
        $tag_2->refresh();

        $this->assertEquals(1, $tag_1->private_attached);
        $this->assertEquals(3, $tag_2->private_attached);
    }

    public function test_update_and_remove_tags()
    {
        $this->loginAsAdmin();

        $tag_1 = $this->tagBuilder->private_attached(3)->create();

        /** @var $model Note */
        $model = $this->noteBuilder->tags($tag_1)->create();

        $data = $this->data;

        $this->assertNotEquals($model->title, $data['title']);
        $this->assertNotEquals($model->slug, slug($data['title']));
        $this->assertNotEquals($model->text, $data['text']);

        $this->assertCount(1, $model->tags);

        $this->putJson(route('api.private.note.update', ['id' => $model->id]), $data)
            ->assertJson([
                'id' => $model->id,
                'title' => $data['title'],
                'slug' => slug($data['title']),
                'text' => $data['text'],
            ])
            ->assertJsonCount(0, 'tags')
//            ->assertValidResponse(200)
        ;

        $tag_1->refresh();

        $this->assertEquals(3, $tag_1->private_attached);
    }

    public function test_update_not_unique_name()
    {
        $this->loginAsAdmin();

        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $data = $this->data;
        $data['title'] = $model->title;

        $this->assertEquals($model->title, $data['title']);
        $this->assertNotEquals($model->text, $data['text']);

        $this->putJson(route('api.private.note.update', ['id' => $model->id]), $data)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'id' => $model->id,
                'title' => $data['title'],
                'slug' => slug($data['title']),
                'text' => $data['text'],
            ])
        ;
    }

    public function test_fail_unique_name()
    {
        $this->loginAsAdmin();

        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $anotherModel = $this->noteBuilder->create();

        $data = $this->data;
        $data['title'] = $anotherModel->title;

        $res = $this->putJson(route('api.private.note.update', ['id' => $model->id]), $data)
        ;

        self::assertValidationError(
            $res,
            __('validation.attributes.title'),
            __('validation.unique', ['attribute' => __('validation.attributes.title')])
        );
    }

    public function test_fail_not_found_model()
    {
        $this->loginAsAdmin();

        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $data = $this->data;

        $res = $this->putJson(route('api.private.note.update', ['id' => $model->id + 1]), $data)
        ;

        self::assertNotFound($res);
    }

    public function test_not_auth()
    {
        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $data = $this->data;

        $res = $this->putJson(route('api.private.note.update', ['id' => $model->id]), $data)
        ;

        self::assertUnauthorized($res);
    }
}
