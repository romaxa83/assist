<?php

namespace App\Http\Controllers\Api\Notes;

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
    #[Parameters\ParameterSort(
        modelClass: Note::class,
    )]
    #[Parameters\ParameterSearch]
    #[Parameters\ParameterStartDate]
    #[Parameters\ParameterEndDate]
    #[Parameters\ParameterEnum(
        parameter: 'status',
        enum:NoteStatus::class,
        description: 'Statuses can be obtained upon request to the route - /api/settings/notes'
    )]
    #[Parameters\ParameterIntArray(
        parameter: 'tags'
    )]
    #[Responses\ResponsePaginate(NoteResource::class)]
    #[Responses\ResponseServerError]
    public function index(NoteFilterRequest $request): ResourceCollection
    {
        $filter = $request->validated();
//dd($filter);
        $recsQuery = Note::query()
            ->filter($filter)
        ;

        if($search = $filter['search'] ?? null){
            $recsQuery->search($search);
        } else {
            if(isset($filter['sort'])){
                foreach ($filter['sort'] as $item){
                    $item = explode('-', $item);
                    $recsQuery->orderBy($item[0], $item[1]);
                }
            } else {
                $recsQuery->orderBy(Note::DEFAULT_SORT_FIELD, Note::DEFAULT_SORT_TYPE);
            }
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
    )]
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
