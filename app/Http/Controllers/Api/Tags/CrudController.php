<?php

namespace App\Http\Controllers\Api\Tags;

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
use OpenAPI\Request\RequestJson;
use OpenAPI\Responses;
use OpenAPI\Parameters;

class CrudController extends ApiController
{
    public function __construct(
        protected TagService $service
    )
    {}

    #[Operation\ApiGet(
        path: '/api/tags',
        tags: ['Tags'],
        description: 'Get tags as list',
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
            ->orderBy('weight', 'desc')
            ->get()
        ;

        return TagResource::collection($recs);
    }

    #[Operation\ApiPost(
        path: '/api/tags',
        tags: ['Tags'],
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
        path: '/api/tags/{id}',
        tags: ['Tags'],
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
        path: '/api/tags/{id}',
        tags: ['Tags'],
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
