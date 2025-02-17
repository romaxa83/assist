<?php

namespace App\Http\Controllers\Api\Notes\Public;

use App\Enums\Notes\NoteStatus;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Notes\NotePublicRequest;
use App\Http\Resources\Api\Notes\NotePublicFullResource;
use App\Http\Resources\Api\Notes\NotePublicSimpleResource;
use App\Models\Notes\Note;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenAPI\Operation;
use OpenAPI\Parameters;
use OpenAPI\Responses;

class Controller extends ApiController
{
    public function __construct()
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
    #[Parameters\ParameterStringArray(
        parameter: 'tags',
        description: 'Search by tags via SLUG'
    )]
    #[Responses\ResponsePaginate(NotePublicSimpleResource::class)]
    #[Responses\ResponseServerError]
    public function index(NotePublicRequest $request): ResourceCollection
    {
        $filter = $request->validated();
        $filter['status'] = [NoteStatus::PUBLIC()];
        if(isset($filter['tags'])) {
            $filter['tags_slug'] = $filter['tags'];
            unset($filter['tags']);
        }

        $recsQuery = Note::query()
            ->filter($filter)
        ;

        if($search = $filter['search'] ?? null){
            $recsQuery->search($search);
        } else {
            $recsQuery->orderBy(Note::DEFAULT_SORT_FIELD, Note::DEFAULT_SORT_TYPE);
        }

        $recs = $recsQuery->paginate(
            page: $filter['page'] ?? 1,
            perPage: $filter['per_page'] ?? Note::DEFAULT_PER_PAGE,
        );

        return NotePublicSimpleResource::collection($recs);
    }

    #[Operation\ApiGet(
        path: '/api/notes/{slug}',
        tags: ['Notes'],
        description: 'Get note by slug',
    )]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[Parameters\ParameterSlug]
    #[Responses\ResponseJsonSuccess(NotePublicFullResource::class)]
    #[Responses\ResponseServerError]
    public function show($slug): NotePublicFullResource
    {
        $model = Note::query()
            ->where('slug', $slug)
            ->firstOrFail()
        ;

        return NotePublicFullResource::make($model);
    }

}

