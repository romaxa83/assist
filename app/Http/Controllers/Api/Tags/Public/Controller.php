<?php

namespace App\Http\Controllers\Api\Tags\Public;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Tags\TagFilterRequest;
use App\Http\Resources\Api\Tags\TagPublicResource;
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
    #[Responses\ResponseCollection(TagPublicResource::class)]
    #[Responses\ResponseServerError]
    public function index(TagFilterRequest $request): ResourceCollection
    {
        $queryBuilder = Tag::query()
            ->filter($request->validated())
        ;

        if(auth_user()){
            $queryBuilder
                ->where('private_attached', '>', 0)
                ->orderBy('private_attached', Tag::DEFAULT_SORT_TYPE);
        } else {
            $queryBuilder
                ->where('public_attached', '>', 0)
                ->orderBy('public_attached', Tag::DEFAULT_SORT_TYPE);
        }

        return TagPublicResource::collection(
            $queryBuilder->get()
        );
    }
}

