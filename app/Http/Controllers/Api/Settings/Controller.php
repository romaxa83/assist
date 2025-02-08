<?php

namespace App\Http\Controllers\Api\Settings;

use App\Dto\Notes\NoteDto;
use App\Enums\Notes\NoteStatus;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Notes\NoteFilterRequest;
use App\Http\Requests\Api\Notes\NoteRequest;
use App\Http\Resources\Api\Notes\NoteResource;
use App\Models\Notes\Note;
use App\Services\Notes\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use OpenAPI\Operation;
use OpenAPI\Request\RequestJson;
use OpenAPI\Responses;
use OpenAPI\Parameters;

class Controller extends ApiController
{
    public function __construct(
        protected NoteService $service
    )
    {}

//    #[Operation\ApiGet(
//        path: '/api/settings/notes',
//        tags: ['Settings'],
//        description: 'Get additional data for notes',
//    )]
//    #[Parameters\Headers\Authorization]
//    #[Parameters\Headers\ContentType]
//    #[Parameters\Headers\Accept]
//
//
//    #[Responses\ResponsePaginate(NoteResource::class)]
//    #[Responses\ResponseServerError]
    public function notes(): JsonResponse
    {
        $data = [
            'statuses' => NoteStatus::getValuesLabels()
        ];

        return self::jsonData($data);
    }

}

