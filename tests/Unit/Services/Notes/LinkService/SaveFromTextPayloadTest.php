<?php

namespace Tests\Unit\Services\Notes\LinkService;

use App\Models\Notes\Note;
use App\Services\Notes\NoteLinkService;
use App\Services\TextProcess\TextPayload;
use Tests\Builders\Notes\LinkBuilder;
use Tests\Builders\Notes\NoteBuilder;
use Tests\TestCase;

class SaveFromTextPayloadTest extends TestCase
{

    protected NoteLinkService $service;

    protected NoteBuilder $noteBuilder;
    protected LinkBuilder $linkBuilder;
    public function setUp(): void
    {
        parent::setUp();
        $this->noteBuilder = resolve(NoteBuilder::class);
        $this->linkBuilder = resolve(LinkBuilder::class);
        $this->service = resolve(NoteLinkService::class);
    }

    public function test_create_links(): void
    {
        $data = [
            [
                'link' => 'https://www.php-fig.org/psr/psr-1/',
                'name' => 'PSR-1',
                'is_external' => true,
                'to_id' => null,
                'attributes' => []
            ],
            [
                'link' => '/notes/85',
                'name' => 'PSR-2',
                'is_external' => false,
                'to_id' => 85,
                'attributes' => [
                    "class" => "inner_link"
                ]
            ]
        ];

        $payload = new TextPayload('');
        $payload->links = $data;

        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $this->assertEmpty($model->links);

        $this->service->saveFromTextPayload($payload, $model);

        $model->refresh();

        $this->assertCount(2, $model->links);

        $target_1 = $model->links->where('link', $data[0]['link'])->first();

        $this->assertEquals($target_1->name, $data[0]['name']);
        $this->assertEquals($target_1->is_external, $data[0]['is_external']);
        $this->assertEquals($target_1->to_note_id, $data[0]['to_id']);
        $this->assertFalse($target_1->active);
        $this->assertEquals($target_1->attributes, ["class" => "inactive"]);
        $this->assertEquals($target_1->reasons, ['system.note_links.inactive.reasons.no_check']);

        $target_2 = $model->links->where('link', $data[1]['link'])->first();

        $this->assertEquals($target_2->name, $data[1]['name']);
        $this->assertEquals($target_2->is_external, $data[1]['is_external']);
        $this->assertEquals($target_2->to_note_id, $data[1]['to_id']);
        $this->assertFalse($target_2->active);
        $this->assertEquals($target_2->attributes, ["class" => "inner_link inactive"]);
        $this->assertEquals($target_2->reasons, ['system.note_links.inactive.reasons.no_check']);
    }

    public function test_update_exist_links(): void
    {
        $data = [
            [
                'link' => 'https://www.php-fig.org/psr/psr-1/',
                'name' => 'PSR-1',
                'is_external' => true,
                'to_id' => null,
                'attributes' => []
            ],
        ];

        $payload = new TextPayload('');
        $payload->links = $data;

        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $link_1 = $this->linkBuilder
            ->note($model)
            ->link($data[0]['link'])
            ->create();

        $link_2 = $this->linkBuilder
            ->note($model)
            ->create();

        $this->assertCount(2, $model->links);

        $this->assertNotEquals($link_1->name, $data[0]['name']);

        $this->service->saveFromTextPayload($payload, $model);

        $model->refresh();
        $link_1->refresh();

        $this->assertCount(1, $model->links);

        $this->assertEquals($link_1->name, $data[0]['name']);
    }

    public function test_delete_links(): void
    {
        $data = [];

        $payload = new TextPayload('');
        $payload->links = $data;

        /** @var $model Note */
        $model = $this->noteBuilder->create();

       $this->linkBuilder
            ->note($model)
            ->create();
        $this->linkBuilder
            ->note($model)
            ->create();

        $this->assertCount(2, $model->links);

        $this->service->saveFromTextPayload($payload, $model);

        $model->refresh();

        $this->assertEmpty($model->links);
    }

    public function test_create_not_links(): void
    {
        $data = [];

        $payload = new TextPayload('');
        $payload->links = $data;

        /** @var $model Note */
        $model = $this->noteBuilder->create();

        $this->assertEmpty($model->links);

        $this->service->saveFromTextPayload($payload, $model);

        $model->refresh();

        $this->assertEmpty($model->links);
    }
}
