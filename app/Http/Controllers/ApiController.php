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
    ) {
        if (is_null($msg)) {
            $msg = 'Success';
        }

        return response()->json([
            'data' => $msg,
        ], $statusCode);
    }

    public static function errorJsonMessage(
        null|string $msg = null,
        $code = Response::HTTP_INTERNAL_SERVER_ERROR
    ) {
        if (is_null($msg)) {
            $msg = 'Error';
        }

        if ($code === 0) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json([
            'success' => false,
            'msg' => $msg,
        ], $code);
    }
}
