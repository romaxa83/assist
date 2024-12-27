<?php

namespace App\Http\Controllers\Api\Tags;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenAPI\Operation\ApiGet;


class CrudController extends ApiController
{

//    #[ApiGet(
//        path: '/api/tags',
//        tags: ['Auth'],
//        description: 'Get tags',
//        auth: true
//    )]
    public function index(): JsonResponse
    {
        return $this->successJsonMessage('Tags retrieved successfully');
    }
}
