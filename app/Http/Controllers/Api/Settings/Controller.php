<?php

namespace App\Http\Controllers\Api\Settings;

use App\Enums\Notes\NoteStatus;
use App\Http\Controllers\ApiController;
use App\Services\Notes\NoteService;
use Illuminate\Http\JsonResponse;
use OpenAPI\Operation;
use OpenAPI\Responses;
use OpenAPI\Parameters;

class Controller extends ApiController
{
    public function __construct(
        protected NoteService $service
    )
    {}

    #[Operation\ApiGet(
        path: '/api/settings/notes',
        tags: ['Settings'],
        description: 'Get additional data for notes',
    )]
    #[Parameters\Headers\Authorization]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[Responses\Settings\ResponseNoteSettings]
    #[Responses\ResponseServerError]
    public function notes(): JsonResponse
    {
        $data = [
            'statuses' => NoteStatus::getValuesLabels()
        ];

        return self::jsonData($data);
    }
}