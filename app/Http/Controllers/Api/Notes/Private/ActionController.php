<?php

namespace App\Http\Controllers\Api\Notes\Private;

use App\Enums\Notes\NoteStatus;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Notes\NoteSetStatusRequest;
use App\Http\Resources\Api\Notes\NoteResource;
use App\Services\Notes\NoteService;
use OpenAPI\Operation;
use OpenAPI\Parameters;
use OpenAPI\Request\RequestJson;
use OpenAPI\Responses;

class ActionController extends ApiController
{
    public function __construct(
        protected NoteService $service
    )
    {}

    #[Operation\ApiPost(
        path: '/api/private/notes/{id}/set-status',
        tags: ['Notes private'],
        description: 'Set status for note',
        auth: true
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
