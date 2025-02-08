<?php

namespace App\Http\Controllers\Api\Notes;

use App\Dto\Notes\NoteDto;
use App\Enums\Notes\NoteStatus;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Notes\NoteFilterRequest;
use App\Http\Requests\Api\Notes\NoteRequest;
use App\Http\Requests\Api\Notes\NoteSetStatusRequest;
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

class ActionController extends ApiController
{
    public function __construct(
        protected NoteService $service
    )
    {}

    #[Operation\ApiPost(
        path: '/api/notes/{id}/set-status',
        tags: ['Notes'],
        description: 'Set status for note',
    )]
    #[Parameters\Headers\Authorization]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[RequestJson(NoteSetStatusRequest::class)]
    #[Responses\ResponseJsonSuccess(NoteResource::class)]
    #[Responses\ResponseNotFound]
    #[Responses\ResponseInvalid]
    #[Responses\ResponseServerError]
    public function setStatus(NoteSetStatusRequest $request): NoteResource
    {
        $status = NoteStatus::from($request->validated()['status']);

        return NoteResource::make(
            $this->service->setStatus($request->getNote(), $status)
        );
    }
}
