<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'success' => true,
            'data' => parent::toArray($request),
        ];
    }

    protected function transformResource(Request $request): array
    {
        return [];
    }

//    public function toResponse($request)
//    {
//        dd('f');
//        if ($this->resource instanceof ResourceCollection) {
//            return $this->resource->toResponse($request);
//        }
//
//        return parent::toResponse($request);
//    }
}
