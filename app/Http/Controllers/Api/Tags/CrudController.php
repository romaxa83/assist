<?php

namespace App\Http\Controllers\Api\Tags;

use App\Dto\Tags\TagDto;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Tags\TagRequest;
use App\Http\Resources\Api\Tags\TagResource;
use App\Http\Resources\Api\Tags\TagResourceCollection;
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
    public function index()
    {
        $recs = Tag::query()
            ->orderBy('weight', 'desc')
            ->get()
        ;

        return TagResource::collection($recs)
            ->additional(['is_single' => false]);
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

        return (new TagResource(
            $this->service->update(
                Tag::query()->findOrFail($id),
                $dto
            )
        ))->additional(['is_single' => true])
    ;
    }

    public function delete($id): JsonResponse
    {
        $this->service->delete(Tag::query()->findOrFail($id));

        return self::successJsonMessage('Deleted');
    }
}
