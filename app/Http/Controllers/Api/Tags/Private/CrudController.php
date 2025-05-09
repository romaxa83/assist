<?php

namespace App\Http\Controllers\Api\Tags\Private;

use App\Dto\Tags\TagDto;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Tags\TagFilterRequest;
use App\Http\Requests\Api\Tags\TagRequest;
use App\Http\Resources\Api\Tags\TagResource;
use App\Models\Tags\Tag;
use App\Services\Tags\TagService;
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
        protected TagService $service
    )
    {}

    #[Operation\ApiGet(
        path: '/api/private/tags',
        tags: ['Tags private'],
        description: 'Get tags as list',
        auth: true
    )]
    #[Parameters\Headers\Authorization]
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

    #[Operation\ApiPost(
        path: '/api/private/tags',
        tags: ['Tags private'],
        description: 'Create tag',
        auth: true
    )]
    #[Parameters\Headers\Authorization]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]

    #[RequestJson(TagRequest::class)]

    #[Responses\ResponseJsonSuccess(
        resource:TagResource::class,
        response: Response::HTTP_CREATED
    )]
    #[Responses\ResponseInvalid]
    #[Responses\ResponseServerError]
    public function create(TagRequest $request): TagResource
    {
        $dto = TagDto::byArgs($request->validated());

        return TagResource::make(
            $this->service->create($dto)
        );
    }

    #[Operation\ApiPut(
        path: '/api/private/tags/{id}',
        tags: ['Tags private'],
        description: 'Update tag',
        auth: true
    )]
    #[Parameters\Headers\Authorization]
    #[Parameters\Headers\ContentType]
    #[Parameters\Headers\Accept]
    #[Parameters\ParameterId]
    #[RequestJson(TagRequest::class)]
    #[Responses\ResponseJsonSuccess(TagResource::class)]
    #[Responses\ResponseNotFound]
    #[Responses\ResponseInvalid]
    #[Responses\ResponseServerError]
    public function update(TagRequest $request, $id): TagResource
    {
        $dto = TagDto::byArgs($request->validated());

        return TagResource::make(
            $this->service->update(
                Tag::query()->findOrFail($id),
                $dto
            )
        );
    }

    #[Operation\ApiDelete(
        path: '/api/private/tags/{id}',
        tags: ['Tags private'],
        description: 'Delete tag',
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
        $this->service->delete(Tag::query()->findOrFail($id));

        return self::noContent();
    }
}
