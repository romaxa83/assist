<?php

namespace App\Http\Controllers\Api\Notes;

use App\Dto\Notes\NoteDto;
use App\Dto\Tags\TagDto;
use App\Enums\Notes\NoteStatus;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Notes\NoteFilterRequest;
use App\Http\Requests\Api\Notes\NoteRequest;
use App\Http\Requests\Api\Tags\TagFilterRequest;
use App\Http\Requests\Api\Tags\TagRequest;
use App\Http\Resources\Api\Notes\NoteResource;
use App\Http\Resources\Api\Tags\TagResource;
use App\Models\Notes\Note;
use App\Models\Tags\Tag;
use App\Services\Notes\NoteService;
use App\Services\Tags\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use OpenAPI\Operation;
use OpenAPI\Request\RequestJson;
use OpenAPI\Responses;
use OpenAPI\Parameters;

class CrudController extends ApiController
{
    public function __construct(
        protected NoteService $service
    )
    {}

    #[Operation\ApiGet(
        path: '/api/notes',
        tags: ['Notes'],
        description: 'Get notes as pagination list',
    )]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[Parameters\ParameterPage]
    #[Parameters\ParameterPerPage]
    #[Parameters\ParameterSearch]
    #[Parameters\ParameterIntArray(
        parameter: 'tags'
    )]
    #[Responses\ResponsePaginate(NoteResource::class)]
    #[Responses\ResponseServerError]
    public function index(NoteFilterRequest $request): ResourceCollection
    {
        $filter = $request->validated();

        $recsQuery = Note::query()
            ->filter($filter)
        ;

        if($search = $filter['search'] ?? null){
            $recsQuery->search($search);
        } else {
            $recsQuery->orderBy('weight', 'desc');
        }

        $recs = $recsQuery->paginate(
            page: $filter['page'] ?? 1,
            perPage: $filter['per_page'] ?? Note::DEFAULT_PER_PAGE,
        );

        return NoteResource::collection($recs);
    }

    #[Operation\ApiGet(
        path: '/api/notes/shortlist',
        tags: ['Notes'],
        description: 'Get notes as list',
        auth: true
    )]
    #[Parameters\Headers\Authorization]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[Parameters\ParameterSearch]
    #[Responses\ResponseCollection(NoteResource::class)]
    #[Responses\ResponseServerError]
    public function shortlist(NoteFilterRequest $request): ResourceCollection
    {
        $recs = Note::query()
            ->filter($request->validated())
            ->orderBy('weight', 'desc')
            ->get()
        ;

        return NoteResource::collection($recs);
    }

    #[Operation\ApiGet(
        path: '/api/notes/{id}',
        tags: ['Notes'],
        description: 'Get note by id',
        auth: true
    )]
    #[Parameters\Headers\Authorization]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[Parameters\ParameterId]
    #[Responses\ResponseJsonSuccess(NoteResource::class)]
    #[Responses\ResponseServerError]
    public function show($id): NoteResource
    {
        $model = Note::query()
            ->where('id', $id)
            ->firstOrFail()
        ;

        return NoteResource::make($model);
    }

    #[Operation\ApiPost(
        path: '/api/notes',
        tags: ['Notes'],
        description: 'Create note',
        auth: true
    )]
    #[Parameters\Headers\Authorization]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[RequestJson(NoteRequest::class)]
    #[Responses\ResponseJsonSuccess(
        resource:NoteResource::class,
        response: Response::HTTP_CREATED
    )]
    #[Responses\ResponseInvalid]
    #[Responses\ResponseServerError]
    public function create(NoteRequest $request): NoteResource
    {
        $dto = NoteDto::byArgs($request->validated());

        return NoteResource::make(
            $this->service->create($dto)
        );
    }

    #[Operation\ApiPut(
        path: '/api/notes/{id}',
        tags: ['Notes'],
        description: 'Update note',
        auth: true
    )]
    #[Parameters\Headers\Authorization]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[Parameters\ParameterId]
    #[RequestJson(NoteRequest::class)]
    #[Responses\ResponseJsonSuccess(NoteResource::class)]
    #[Responses\ResponseNotFound]
    #[Responses\ResponseInvalid]
    #[Responses\ResponseServerError]
    public function update(NoteRequest $request, $id): NoteResource
    {
        $dto = NoteDto::byArgs($request->validated());

        return NoteResource::make(
            $this->service->update(
                Note::query()->findOrFail($id),
                $dto
            )
        );
    }

    #[Operation\ApiDelete(
        path: '/api/notes/{id}',
        tags: ['Notes'],
        description: 'Delete note',
        auth: true
    )]
    #[Parameters\Headers\Authorization]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[Parameters\ParameterId]
    #[Responses\ResponseNoContent]
    #[Responses\ResponseNotFound]
    #[Responses\ResponseServerError]
    public function delete($id): JsonResponse
    {
        $this->service->delete(
            Note::query()->findOrFail($id)
        );

        return self::noContent();
    }
}
