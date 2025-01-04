<?php

namespace Tests\Feature\Api\Notes\CrudController;

use App\Models\Notes\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\Notes\NoteBuilder;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;

    protected NoteBuilder $noteBuilder;

    protected array $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->noteBuilder = resolve(NoteBuilder::class);

        $this->data = [
            'title' => 'test title',
            'text' => 'some text',
        ];
    }

    public function test_success()
    {
        $this->loginAsAdmin();

        $data = $this->data;

        $this->postJson(route('api.note.create'), $data)
            ->assertValidResponse(201)
        ;
    }

    public function test_fail_unique_name()
    {
        $this->loginAsAdmin();

        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $data = $this->data;
        $data['title'] = $model->title;

        $res = $this->postJson(route('api.note.create'), $data)
        ;

        self::assertValidationError(
            $res,
            __('validation.attributes.title'),
            __('validation.unique', ['attribute' => __('validation.attributes.title')])
        );
    }

    public function test_fail_not_auth()
    {
        $data = $this->data;

        $res = $this->postJson(route('api.note.create'), $data)
        ;

        self::assertUnauthorized($res);
    }
}
