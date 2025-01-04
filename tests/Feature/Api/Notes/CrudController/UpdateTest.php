<?php

namespace Tests\Feature\Api\Notes\CrudController;

use App\Models\Notes\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\Builders\Notes\NoteBuilder;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    protected NoteBuilder $noteBuilder;

    protected array $data;

    public function setUp(): void
    {
        $this->noteBuilder = resolve(NoteBuilder::class);

        $this->data = [
            'title' => 'test title',
            'text' => 'some text',
        ];
        parent::setUp();
    }

    public function test_update()
    {
        $this->loginAsAdmin();

        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $data = $this->data;

        $this->assertNotEquals($model->title, $data['title']);
        $this->assertNotEquals($model->slug, slug($data['title']));
        $this->assertNotEquals($model->text, $data['text']);

        $this->putJson(route('api.note.update', ['id' => $model->id]), $data)
            ->assertJson([
                'id' => $model->id,
                'title' => $data['title'],
                'slug' => slug($data['title']),
                'text' => $data['text'],
            ])
            ->assertValidResponse(200)
        ;
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

        $this->putJson(route('api.note.update', ['id' => $model->id]), $data)
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

        $res = $this->putJson(route('api.note.update', ['id' => $model->id]), $data)
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

        $res = $this->putJson(route('api.note.update', ['id' => $model->id + 1]), $data)
        ;

        self::assertNotFound($res);
    }

    public function test_fail_not_auth()
    {
        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $data = $this->data;

        $res = $this->putJson(route('api.note.update', ['id' => $model->id]), $data)
        ;

        self::assertUnauthorized($res);
    }
}
