<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'success' => true,
            'data' => $this->transformResource($request)
        ];
    }

    protected function transformResource(Request $request): array
    {
        return [];
    }
}
