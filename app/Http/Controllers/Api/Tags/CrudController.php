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
use OpenAPI\Operation\ApiGet;

class CrudController extends ApiController
{
    public function __construct(
        protected TagService $service
    )
    {}

//    #[ApiGet(
//        path: '/api/tags',
//        tags: ['Auth'],
//        description: 'Get tags',
//        auth: true
//    )]
    public function index(TagFilterRequest $request): ResourceCollection
    {
        $recs = Tag::query()
            ->filter($request->validated())
            ->orderBy('weight', 'desc')
            ->get()
        ;

        return TagResource::collection($recs);
    }

    public function create(TagRequest $request): TagResource
    {
        $dto = TagDto::byArgs($request->validated());

        return TagResource::make(
            $this->service->create($dto)
        );
    }

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

    public function delete($id): JsonResponse
    {
        $this->service->delete(Tag::query()->findOrFail($id));

        return self::successJsonMessage('Deleted');
    }
}
