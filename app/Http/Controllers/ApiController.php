<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    public function info(): JsonResponse
    {
        return response()->json([
            'version' => '1.0',
        ], Response::HTTP_OK);
    }

    public static function successJsonMessage(
        null|string $msg = null,
        string|int $statusCode = Response::HTTP_OK
    ): JsonResponse
    {
        if (is_null($msg)) {
            $msg = 'Success';
        }

        return response()->json([
            'success' => true,
            'data' => ['msg' => $msg,],
        ], $statusCode);
    }

    public static function errorJsonMessage(
        null|string $msg = null,
        $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        $stackTrace = []
    ): JsonResponse
    {
        if (is_null($msg)) {
            $msg = 'Error';
        }

        if ($code === 0) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $data = [
            'success' => false,
            'msg' => $msg,
        ];

        return response()->json($data, $code);
    }
}
