<?php

namespace App\Http\Controllers\Api\Notes\Private;

use App\Dto\Notes\NoteDto;
use App\Enums\Notes\NoteStatus;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Notes\NoteFilterRequest;
use App\Http\Requests\Api\Notes\NoteRequest;
use App\Http\Resources\Api\Notes\NotePrivateFullResource;
use App\Http\Resources\Api\Notes\NotePrivateShortResource;
use App\Http\Resources\Api\Notes\NotePrivateSimpleResource;
use App\Models\Notes\Note;
use App\Services\Notes\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use OpenAPI\Operation;
use OpenAPI\Parameters;
use OpenAPI\Request\RequestJson;
use OpenAPI\Responses;

class CrudController extends ApiController
{
    public function __construct(
        protected NoteService $service
    )
    {}

    #[Operation\ApiGet(
        path: '/api/private/notes',
        tags: ['Notes private'],
        description: 'Get notes as pagination list',
        auth: true
    )]
    #[Parameters\Headers\Authorization]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[Parameters\ParameterPage]
    #[Parameters\ParameterPerPage]
    #[Parameters\ParameterSort(
        modelClass: Note::class,
    )]
    #[Parameters\ParameterSearch]
    #[Parameters\ParameterString(
        parameter: 'search_title',
        description: 'Search by title via LIKE'
    )]
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
    #[Responses\ResponsePaginate(NotePrivateSimpleResource::class)]
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

        return NotePrivateSimpleResource::collection($recs);
    }

    #[Operation\ApiGet(
        path: '/api/private/notes/shortlist',
        tags: ['Notes private'],
        description: 'Get notes as list',
        auth: true
    )]
    #[Parameters\Headers\Authorization]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[Parameters\ParameterSearch]
    #[Parameters\ParameterString(
        parameter: 'search_title',
        description: 'Search by title via LIKE'
    )]
    #[Responses\ResponseCollection(NotePrivateShortResource::class)]
    #[Responses\ResponseServerError]
    public function shortlist(NoteFilterRequest $request): ResourceCollection
    {
        $recs = Note::query()
            ->filter($request->validated())
            ->orderBy('weight', 'desc')
            ->get()
        ;

        return NotePrivateShortResource::collection($recs);
    }

    #[Operation\ApiGet(
        path: '/api/private/notes/{id}',
        tags: ['Notes private'],
        description: 'Get note by id',
        auth: true
    )]
    #[Parameters\Headers\Authorization]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[Parameters\ParameterId]
    #[Responses\ResponseJsonSuccess(NotePrivateFullResource::class)]
    #[Responses\ResponseServerError]
    public function show($id): NotePrivateFullResource
    {
        $model = Note::query()
            ->where('id', $id)
            ->firstOrFail()
        ;

        return NotePrivateFullResource::make($model);
    }

    #[Operation\ApiPost(
        path: '/api/private/notes',
        tags: ['Notes private'],
        description: 'Create note',
        auth: true
    )]
    #[Parameters\Headers\Authorization]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[RequestJson(NoteRequest::class)]
    #[Responses\ResponseJsonSuccess(
        resource:NotePrivateFullResource::class,
        response: Response::HTTP_CREATED
    )]
    #[Responses\ResponseInvalid]
    #[Responses\ResponseServerError]
    public function create(NoteRequest $request): NotePrivateFullResource
    {
        $dto = NoteDto::byArgs($request->validated());

        return NotePrivateFullResource::make(
            $this->service->create($dto)
        );
    }

    #[Operation\ApiPut(
        path: '/api/private/notes/{id}',
        tags: ['Notes private'],
        description: 'Update note',
        auth: true
    )]
    #[Parameters\Headers\Authorization]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[Parameters\ParameterId]
    #[RequestJson(NoteRequest::class)]
    #[Responses\ResponseJsonSuccess(NotePrivateFullResource::class)]
    #[Responses\ResponseNotFound]
    #[Responses\ResponseInvalid]
    #[Responses\ResponseServerError]
    public function update(NoteRequest $request, $id): NotePrivateFullResource
    {
        $dto = NoteDto::byArgs($request->validated());

        return NotePrivateFullResource::make(
            $this->service->update(
                Note::query()->findOrFail($id),
                $dto
            )
        );
    }

    #[Operation\ApiDelete(
        path: '/api/private/notes/{id}',
        tags: ['Notes private'],
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
