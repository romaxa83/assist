<?php

namespace App\Http\Controllers\Api\Tags\Public;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Tags\TagFilterRequest;
use App\Http\Resources\Api\Tags\TagResource;
use App\Models\Tags\Tag;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenAPI\Operation;
use OpenAPI\Parameters;
use OpenAPI\Responses;

class Controller extends ApiController
{
    public function __construct()
    {}

    #[Operation\ApiGet(
        path: '/api/tags',
        tags: ['Tags'],
        description: 'Get tags as list'
    )]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[Parameters\ParameterSearch]
    #[Responses\ResponseCollection(TagResource::class)]
    #[Responses\ResponseServerError]
    public function index(TagFilterRequest $request): ResourceCollection
    {
        $recs = Tag::query()
            ->filter($request->validated())
            ->orderBy(Tag::DEFAULT_SORT_FIELD, Tag::DEFAULT_SORT_TYPE)
            ->get()
        ;

        return TagResource::collection($recs);
    }
}

